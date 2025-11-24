<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['employee.user', 'employee.shift'])
            ->whereDate('date', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('attendance.index', compact('attendances'));
    }

    public function mark(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'You are not registered as an employee.');
        }

        // Check if already marked today
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('warning', 'Attendance already marked for today.');
        }

        $now = Carbon::now();
        $shift = $employee->shift;

        $status = 'Present';
        if ($shift) {
            $shiftStart = Carbon::parse($shift->start_time);
            $graceTime = $shiftStart->addMinutes($shift->grace_period_minutes);

            if ($now->gt($graceTime)) {
                $status = 'Late';
            }
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => Carbon::today(),
            'check_in_time' => $now->format('H:i:s'),
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully!');
    }
}
