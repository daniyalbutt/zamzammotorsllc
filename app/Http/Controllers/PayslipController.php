<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use App\Models\User;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Payslip::with('user')->get();
        return view('payslips.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = null;
        $employee = User::role('employee')->get();
        return view('payslips.create', compact('data', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'date_issued' => 'nullable|date',
            'due_date' => 'nullable|date',
            'earnings.*.name' => 'required|string',
            'earnings.*.amount' => 'required|numeric|min:0',
            'deductions.*.name' => 'required|string',
            'deductions.*.amount' => 'required|numeric|min:0',
        ]);

        $payslip = Payslip::create([
            'user_id' => $request->user_id,
            'month' => $request->month,
            'date_issued' => $request->date_issued,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'total' => $request->total,
        ]);

        // Save earnings
        foreach ($request->earnings as $earning) {
            $payslip->payslipsData()->create([
                'name' => $earning['name'],
                'description' => $earning['description'],
                'pay_type' => $earning['pay_type'],
                'type' => 'earning',
                'amount' => $earning['amount'],
            ]);
        }

        // Save deductions
        foreach ($request->deductions as $deduction) {
            $payslip->payslipsData()->create([
                'name' => $deduction['name'],
                'description' => $deduction['description'],
                'pay_type' => $deduction['pay_type'],
                'type' => 'deduction',
                'amount' => $deduction['amount'],
            ]);
        }

        return redirect()->route('payroll.index')->with('success', 'Payslip created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Payslip::with('user', 'payslipsData')->findOrFail($id);
        return view('payslips.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Payslip::with('payslipsData')->findOrFail($id);
        return view('payslips.create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2030',
            'date_issued' => 'nullable|date',
            'due_date' => 'nullable|date',
            'earnings.*.name' => 'required|string',
            'earnings.*.amount' => 'required|numeric|min:0',
            'deductions.*.name' => 'required|string',
            'deductions.*.amount' => 'required|numeric|min:0',
        ]);

        // Find the payslip to update
        $payslip = Payslip::findOrFail($id);

        // Update the payslip
        $payslip->update([
            'user_id' => $request->user_id,
            'month' => $request->month,
            'date_issued' => $request->date_issued,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'total' => $request->total,
        ]);

        // Delete existing payslip data
        $payslip->payslipsData()->delete();

        // Save earnings
        foreach ($request->earnings as $earning) {
            $payslip->payslipsData()->create([
                'name' => $earning['name'],
                'description' => $earning['description'] ?? null,
                'pay_type' => $earning['pay_type'] ?? '-',
                'type' => 'earning',
                'amount' => $earning['amount'],
            ]);
        }

        // Save deductions
        foreach ($request->deductions as $deduction) {
            $payslip->payslipsData()->create([
                'name' => $deduction['name'],
                'description' => $deduction['description'] ?? null,
                'pay_type' => $deduction['pay_type'] ?? '-',
                'type' => 'deduction',
                'amount' => $deduction['amount'],
            ]);
        }

        return redirect()->route('payroll.index')->with('success', 'Payslip updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
