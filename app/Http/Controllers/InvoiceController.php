<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        $query = Invoice::with(['customer.user', 'vehicle', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%")
                        ->orWhere('stock_id', 'like', "%{$search}%");
                  });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // If Sales Agent, only show their invoices
        if (Auth::user()->hasRole('Sales Agent')) {
            $query->where('created_by', Auth::id());
        }

        $invoices = $query->latest()->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        // Get customers assigned to the agent (if agent) or all customers
        if (Auth::user()->hasRole('Sales Agent')) {
            $customers = Customer::with('user')->where('assigned_to', Auth::id())->get();
        } else {
            $customers = Customer::with('user')->get();
        }

        $vehicles = Vehicle::available()->orWhere('availability', 'Reserved')->get();

        return view('invoices.create', compact('customers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_price' => 'required|numeric|min:0',
            'initial_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'required_with:initial_payment|in:Cash,Bank Transfer,Cheque,Financing,Card,Other',
            'payment_date' => 'required_with:initial_payment|date',
            'payment_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $initialPayment = $validated['initial_payment'] ?? 0;
            $remainingBalance = $validated['vehicle_price'] - $initialPayment;

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 5, '0', STR_PAD_LEFT);

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'vehicle_price' => $validated['vehicle_price'],
                'total_paid' => $initialPayment,
                'remaining_balance' => $remainingBalance,
                'status' => $remainingBalance == 0 ? 'Paid' : ($initialPayment > 0 ? 'Partial' : 'Pending'),
                'created_by' => Auth::id(),
            ]);

            // Add initial payment if provided
            if ($initialPayment > 0) {
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $initialPayment,
                    'payment_method' => $validated['payment_method'],
                    'payment_date' => $validated['payment_date'],
                    'notes' => $validated['payment_notes'],
                    'recorded_by' => Auth::id(),
                ]);
            }

            // Update vehicle status
            $vehicle = Vehicle::find($validated['vehicle_id']);
            if ($remainingBalance == 0) {
                $vehicle->update(['availability' => 'Sold Out']);
            } else {
                $vehicle->update(['availability' => 'Reserved']);
            }

            // Log activity
            ActivityLog::create([
                'action' => 'created',
                'model_type' => 'Invoice',
                'model_id' => $invoice->id,
                'description' => 'Invoice ' . $invoiceNumber . ' was created',
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        // Check if Sales Agent can only view their invoices
        if (Auth::user()->hasRole('Sales Agent') && $invoice->created_by != Auth::id()) {
            abort(403, 'You can only view your own invoices.');
        }

        $invoice->load(['customer.user', 'vehicle', 'payments.recorder', 'creator']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::with('user')->get();
        $vehicles = Vehicle::available()->orWhere('id', $invoice->vehicle_id)->get();
        $invoice->load(['payments']);

        return view('invoices.edit', compact('invoice', 'customers', 'vehicles'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'vehicle_price' => 'required|numeric|min:0',
        ]);

        // Recalculate balance
        $totalPaid = $invoice->payments()->sum('amount');
        $remainingBalance = $validated['vehicle_price'] - $totalPaid;

        $invoice->update([
            'vehicle_price' => $validated['vehicle_price'],
            'remaining_balance' => $remainingBalance,
            'status' => $remainingBalance == 0 ? 'Paid' : ($totalPaid > 0 ? 'Partial' : 'Pending'),
        ]);

        // Log activity
        ActivityLog::create([
            'action' => 'updated',
            'model_type' => 'Invoice',
            'model_id' => $invoice->id,
            'description' => 'Invoice ' . $invoice->invoice_number . ' was updated',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully!');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_balance,
            'payment_method' => 'required|in:Cash,Bank Transfer,Cheque,Financing,Card,Other',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Add payment
            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'],
                'recorded_by' => Auth::id(),
            ]);

            // Update invoice
            $newTotalPaid = $invoice->total_paid + $validated['amount'];
            $newRemainingBalance = $invoice->vehicle_price - $newTotalPaid;

            $invoice->update([
                'total_paid' => $newTotalPaid,
                'remaining_balance' => $newRemainingBalance,
                'status' => $newRemainingBalance == 0 ? 'Paid' : 'Partial',
            ]);

            // Update vehicle status if fully paid
            if ($newRemainingBalance == 0) {
                $invoice->vehicle->update(['availability' => 'Sold Out']);
            }

            // Log activity
            ActivityLog::create([
                'action' => 'payment_added',
                'model_type' => 'Invoice',
                'model_id' => $invoice->id,
                'description' => 'Payment of ' . $validated['amount'] . ' added to invoice ' . $invoice->invoice_number,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return back()->with('success', 'Payment added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to add payment: ' . $e->getMessage()]);
        }
    }

    public function destroy(Invoice $invoice)
    {
        // Log activity
        ActivityLog::create([
            'action' => 'deleted',
            'model_type' => 'Invoice',
            'model_id' => $invoice->id,
            'description' => 'Invoice ' . $invoice->invoice_number . ' was deleted',
            'user_id' => Auth::id(),
        ]);

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer.user', 'vehicle', 'payments', 'creator']);

        // You'll need to create this view
        // $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        // return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');

        return view('invoices.pdf', compact('invoice'));
    }
}
