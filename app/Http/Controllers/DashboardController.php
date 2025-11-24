<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::available()->count(),
            'reserved_vehicles' => Vehicle::reserved()->count(),
            'sold_vehicles' => Vehicle::sold()->count(),
            'total_customers' => Customer::count(),
            'total_invoices' => Invoice::count(),
            'pending_invoices' => Invoice::pending()->count(),
            'total_revenue' => Invoice::where('status', 'Paid')->sum('vehicle_price'),
            'total_employees' => Employee::count(),
            'pending_leaves' => Leave::pending()->count(),
        ];

        $recent_vehicles = Vehicle::with('creator')->latest()->take(5)->get();
        $recent_invoices = Invoice::with(['customer.user', 'vehicle'])->latest()->take(5)->get();
        $announcements = Announcement::active()->latest()->take(3)->get();

        return view('dashboards.admin', compact('stats', 'recent_vehicles', 'recent_invoices', 'announcements'));
    }

    public function salesManagerDashboard()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::available()->count(),
            'reserved_vehicles' => Vehicle::reserved()->count(),
            'sold_vehicles' => Vehicle::sold()->count(),
            'total_customers' => Customer::count(),
            'total_agents' => \App\Models\User::role('Sales Agent')->count(),
            'total_revenue' => Invoice::where('status', 'Paid')->sum('vehicle_price'),
            'monthly_revenue' => Invoice::where('status', 'Paid')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->sum('vehicle_price'),
        ];

        // Get agent performance data
        $sales_agents = \App\Models\User::role('Sales Agent')->get();
        $agent_performance = collect();

        foreach ($sales_agents as $agent) {
            $agent_performance->push([
                'name' => $agent->name,
                'assigned_customers' => Customer::where('assigned_to', $agent->id)->count(),
                'closed_sales' => Invoice::whereHas('customer', function($q) use ($agent) {
                    $q->where('assigned_to', $agent->id);
                })->where('status', 'Paid')->count(),
                'revenue' => Invoice::whereHas('customer', function($q) use ($agent) {
                    $q->where('assigned_to', $agent->id);
                })->where('status', 'Paid')->sum('vehicle_price'),
            ]);
        }

        $recent_vehicles = Vehicle::with('creator')->latest()->take(5)->get();
        $recent_customers = Customer::with(['user', 'assignedAgent'])->latest()->take(5)->get();

        return view('dashboards.sales-manager', compact('stats', 'recent_vehicles', 'recent_customers', 'agent_performance'));
    }

    public function salesAgentDashboard()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $stats = [
            'assigned_customers' => Customer::assignedTo($user->id)->count(),
            'closed_sales' => Invoice::whereHas('customer', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->where('status', 'Paid')->count(),
            'in_negotiation' => Customer::assignedTo($user->id)->where('status', 'In Negotiation')->count(),
            'total_revenue' => Invoice::whereHas('customer', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->where('status', 'Paid')->sum('vehicle_price'),
        ];

        $customers = Customer::with(['user', 'assignedAgent'])
            ->assignedTo($user->id)
            ->latest()
            ->paginate(10);

        return view('dashboards.sales-agent', compact('stats', 'customers'));
    }

    public function hrDashboard()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'total_departments' => \App\Models\Department::count(),
            'pending_leaves' => Leave::where('status', 'Pending')->count(),
            'present_today' => \App\Models\Attendance::whereDate('date', Carbon::today())
                ->where('status', 'Present')
                ->count(),
        ];

        $recent_attendance = \App\Models\Attendance::with(['employee.user'])
            ->whereDate('date', Carbon::today())
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::where('is_active', true)->latest()->take(3)->get();

        return view('dashboards.hr', compact('stats', 'recent_attendance', 'announcements'));
    }

    public function customerDashboard()
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (!$customer) {
            abort(403, 'Customer profile not found');
        }

        $stats = [
            'total_invoices' => $customer->invoices()->count(),
            'pending_payments' => $customer->invoices()->where('status', '!=', 'Paid')->count(),
            'total_paid' => $customer->invoices()->sum('total_paid'),
            'total_balance' => $customer->invoices()->sum('remaining_balance'),
        ];

        $invoices = $customer->invoices()->with('vehicle')->latest()->get();
        $messages = $customer->messages()->with('sender')->latest()->take(10)->get();

        return view('dashboards.customer', compact('stats', 'invoices', 'messages', 'customer'));
    }
}
