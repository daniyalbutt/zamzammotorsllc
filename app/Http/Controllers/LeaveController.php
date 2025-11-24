<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // If user has employee record, show their leaves
        if ($user->employee) {
            $leaves = Leave::where('employee_id', $user->employee->id)
                ->with(['employee.user', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        // If HR/Admin, show all leaves
        else if ($user->hasAnyRole(['Super Admin', 'HR'])) {
            $leaves = Leave::with(['employee.user', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            abort(403, 'Unauthorized access');
        }

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Only employees can request leave
        if (!$user->employee) {
            return redirect()->route('leaves.index')
                ->with('error', 'Only employees can request leave.');
        }

        $leaveTypes = ['Sick Leave', 'Casual Leave', 'Annual Leave', 'Maternity Leave', 'Paternity Leave', 'Unpaid Leave'];

        return view('leaves.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->employee) {
            return redirect()->route('leaves.index')
                ->with('error', 'Only employees can request leave.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Calculate number of days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end dates

        Leave::create([
            'employee_id' => $user->employee->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'],
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $user = Auth::user();

        // Check authorization
        if (!$user->hasAnyRole(['Super Admin', 'HR']) &&
            (!$user->employee || $user->employee->id !== $leave->employee_id)) {
            abort(403, 'Unauthorized access');
        }

        $leave->load(['employee.user', 'approver']);

        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        $user = Auth::user();

        // Only employee who created the leave can edit, and only if still pending
        if (!$user->employee || $user->employee->id !== $leave->employee_id) {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only edit your own leave requests.');
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only edit pending leave requests.');
        }

        $leaveTypes = ['Sick Leave', 'Casual Leave', 'Annual Leave', 'Maternity Leave', 'Paternity Leave', 'Unpaid Leave'];

        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $user = Auth::user();

        // Only employee who created the leave can update, and only if still pending
        if (!$user->employee || $user->employee->id !== $leave->employee_id) {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only update your own leave requests.');
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only update pending leave requests.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Calculate number of days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;

        $leave->update([
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $user = Auth::user();

        // Only employee who created the leave can delete, and only if still pending
        if (!$user->employee || $user->employee->id !== $leave->employee_id) {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only delete your own leave requests.');
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'You can only delete pending leave requests.');
        }

        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    /**
     * Approve a leave request
     */
    public function approve(Leave $leave)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can approve leave requests.');
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Only pending leave requests can be approved.');
        }

        $leave->update([
            'status' => 'Approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request
     */
    public function reject(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403, 'Only HR can reject leave requests.');
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Only pending leave requests can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $leave->update([
            'status' => 'Rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request rejected.');
    }
}
