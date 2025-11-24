<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Build query
        $query = Payroll::with(['employee.user', 'employee.department']);

        // If employee, show only their payrolls
        if ($user->employee) {
            $query->where('employee_id', $user->employee->id);
        }
        // HR/Admin see all
        elseif (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Unauthorized access');
        }

        // Filter by month/year if provided
        if ($request->filled('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('month_year', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get unique months for filter
        $months = Payroll::select('month_year')
            ->distinct()
            ->orderBy('month_year', 'desc')
            ->pluck('month_year');

        return view('payrolls.index', compact('payrolls', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can generate payroll.');
        }

        // Get all active employees
        $employees = Employee::with('user')->get();

        return view('payrolls.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can generate payroll.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month_year' => 'required|date_format:Y-m',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if payroll already exists for this employee and month
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month_year', $validated['month_year'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payroll for this employee and month already exists.');
        }

        // Calculate net salary
        $bonus = $validated['bonus'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $netSalary = $validated['basic_salary'] + $bonus - $deductions;

        Payroll::create([
            'employee_id' => $validated['employee_id'],
            'month_year' => $validated['month_year'],
            'basic_salary' => $validated['basic_salary'],
            'bonus' => $bonus,
            'deductions' => $deductions,
            'net_salary' => $netSalary,
            'status' => 'Pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll generated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        $user = Auth::user();

        // Check authorization
        if (!$user->hasAnyRole(['Super Admin', 'HR']) &&
            (!$user->employee || $user->employee->id !== $payroll->employee_id)) {
            abort(403, 'Unauthorized access');
        }

        $payroll->load(['employee.user', 'employee.department']);

        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can edit payroll.');
        }

        // Can only edit pending payrolls
        if ($payroll->status !== 'Pending') {
            return redirect()->route('payrolls.index')
                ->with('error', 'Only pending payrolls can be edited.');
        }

        $employees = Employee::with('user')->get();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can update payroll.');
        }

        if ($payroll->status !== 'Pending') {
            return redirect()->route('payrolls.index')
                ->with('error', 'Only pending payrolls can be updated.');
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Calculate net salary
        $bonus = $validated['bonus'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $netSalary = $validated['basic_salary'] + $bonus - $deductions;

        $payroll->update([
            'basic_salary' => $validated['basic_salary'],
            'bonus' => $bonus,
            'deductions' => $deductions,
            'net_salary' => $netSalary,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can delete payroll.');
        }

        // Can only delete pending payrolls
        if ($payroll->status !== 'Pending') {
            return redirect()->route('payrolls.index')
                ->with('error', 'Only pending payrolls can be deleted.');
        }

        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    /**
     * Mark payroll as paid
     */
    public function markPaid(Payroll $payroll)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can mark payroll as paid.');
        }

        if ($payroll->status === 'Paid') {
            return redirect()->route('payrolls.index')
                ->with('error', 'This payroll is already marked as paid.');
        }

        $payroll->update([
            'status' => 'Paid',
            'payment_date' => now(),
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll marked as paid successfully.');
    }

    /**
     * Generate payroll for all employees for a specific month
     */
    public function bulkGenerate(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can generate bulk payroll.');
        }

        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
        ]);

        // Get all active employees
        $employees = Employee::whereDoesntHave('payrolls', function ($query) use ($validated) {
            $query->where('month_year', $validated['month_year']);
        })->get();

        if ($employees->isEmpty()) {
            return redirect()->route('payrolls.index')
                ->with('error', 'All employees already have payroll for this month.');
        }

        $count = 0;
        foreach ($employees as $employee) {
            Payroll::create([
                'employee_id' => $employee->id,
                'month_year' => $validated['month_year'],
                'basic_salary' => $employee->salary,
                'bonus' => 0,
                'deductions' => 0,
                'net_salary' => $employee->salary,
                'status' => 'Pending',
            ]);
            $count++;
        }

        return redirect()->route('payrolls.index')
            ->with('success', "Payroll generated for {$count} employee(s).");
    }
}
