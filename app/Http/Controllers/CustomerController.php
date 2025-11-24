<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $query = Customer::with(['user', 'assignedAgent', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('phone', 'like', "%{$search}%");
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // If Sales Agent, only show their assigned customers
        if (Auth::user()->hasRole('Sales Agent')) {
            $query->where('assigned_to', Auth::id());
        }

        $customers = $query->latest()->paginate(15);

        // Get agents for filter
        $agents = User::role('Sales Agent')->get();

        return view('customers.index', compact('customers', 'agents'));
    }

    public function create()
    {
        $sales_agents = User::role('Sales Agent')->get();
        return view('customers.create', compact('sales_agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'lead_source' => 'required|in:Website,WhatsApp,Referral,Walk-in,Facebook,Instagram,Other',
            'status' => 'required|in:Follow-up,In Negotiation,Closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Generate random password
        $password = Str::random(10);

        // Create user account for customer
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);

        // Assign Customer role
        $user->assignRole('Customer');

        // Create customer record
        $customer = Customer::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'lead_source' => $validated['lead_source'],
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'],
            'created_by' => Auth::id(),
        ]);

        // Send credentials email (you'll need to create this mail class)
        // Mail::to($user->email)->send(new CustomerCredentials($user, $password));

        // Notify assigned agent
        if ($validated['assigned_to']) {
            Notification::create([
                'user_id' => $validated['assigned_to'],
                'title' => 'New Customer Assigned',
                'message' => 'Customer "' . $user->name . '" has been assigned to you.',
                'type' => 'customer_assigned',
                'link' => route('customers.show', $customer->id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'action' => 'created',
            'model_type' => 'Customer',
            'model_id' => $customer->id,
            'description' => 'Customer "' . $user->name . '" was created',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully! Login credentials sent to their email.');
    }

    public function show(Customer $customer)
    {
        // Check if Sales Agent can only view their assigned customers
        if (Auth::user()->hasRole('Sales Agent') && $customer->assigned_to != Auth::id()) {
            abort(403, 'You can only view your assigned customers.');
        }

        $customer->load([
            'user',
            'assignedAgent',
            'invoices.vehicle',
            'messages.sender',
            'notes.creator',
            'documents',
        ]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $sales_agents = User::role('Sales Agent')->get();
        $customer->load('user');
        return view('customers.edit', compact('customer', 'sales_agents'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->user_id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'lead_source' => 'required|in:Website,WhatsApp,Referral,Walk-in,Facebook,Instagram,Other',
            'status' => 'required|in:Follow-up,In Negotiation,Closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Update user
        $customer->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Check if agent was changed
        $oldAgent = $customer->assigned_to;
        $newAgent = $validated['assigned_to'];

        // Update customer
        $customer->update([
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'lead_source' => $validated['lead_source'],
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'],
        ]);

        // Notify new agent if reassigned
        if ($oldAgent != $newAgent && $newAgent) {
            Notification::create([
                'user_id' => $newAgent,
                'title' => 'Customer Assigned',
                'message' => 'Customer "' . $customer->user->name . '" has been assigned to you.',
                'type' => 'customer_assigned',
                'link' => route('customers.show', $customer->id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'action' => 'updated',
            'model_type' => 'Customer',
            'model_id' => $customer->id,
            'description' => 'Customer "' . $customer->user->name . '" was updated',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->user->name;

        // Log activity
        ActivityLog::create([
            'action' => 'deleted',
            'model_type' => 'Customer',
            'model_id' => $customer->id,
            'description' => 'Customer "' . $name . '" was deleted',
            'user_id' => Auth::id(),
        ]);

        // Delete user (this will cascade delete customer)
        $customer->user->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function assignAgent(Request $request, Customer $customer)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldAgent = $customer->assigned_to;
        $customer->update(['assigned_to' => $request->assigned_to]);

        // Notify new agent
        Notification::create([
            'user_id' => $request->assigned_to,
            'title' => 'Customer Assigned',
            'message' => 'Customer "' . $customer->user->name . '" has been assigned to you.',
            'type' => 'customer_assigned',
            'link' => route('customers.show', $customer->id),
        ]);

        // Log activity
        ActivityLog::create([
            'action' => 'assigned',
            'model_type' => 'Customer',
            'model_id' => $customer->id,
            'description' => 'Customer "' . $customer->user->name . '" was assigned to agent',
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Customer assigned successfully!');
    }

    /**
     * Check if customer exists by phone/email
     * AJAX endpoint for agent dashboard
     */
    public function checkCustomer(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3',
        ]);

        $search = $request->search;

        // Search by phone or email
        $customers = Customer::with(['user', 'assignedAgent'])
            ->where(function($query) use ($search) {
                $query->where('phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                      });
            })
            ->limit(10)
            ->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'No customer found with this information.'
            ]);
        }

        $results = $customers->map(function($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->user->name,
                'email' => $customer->user->email,
                'phone' => $customer->phone,
                'status' => $customer->status,
                'lead_source' => $customer->lead_source,
                'assigned_agent' => $customer->assignedAgent ? [
                    'id' => $customer->assignedAgent->id,
                    'name' => $customer->assignedAgent->name,
                    'email' => $customer->assignedAgent->email,
                ] : null,
                'is_assigned_to_me' => $customer->assigned_to == Auth::id(),
                'created_at' => $customer->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'found' => true,
            'customers' => $results
        ]);
    }
}
