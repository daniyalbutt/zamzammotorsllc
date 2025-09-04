<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function timeIn()
    {
        $user = Auth::user();
        $userid = $user->id;
        $timein = now()->timestamp; // Use Carbon with timezone

        // Simple date logic for night shifts (midnight to 6 AM belongs to previous day)
        $currentHour = now()->hour;
        if ($currentHour >= 0 && $currentHour <= 6) {
            // Between midnight and 6 AM, use previous day
            $date = now()->subDay()->startOfDay()->timestamp;
        } else {
            $date = now()->startOfDay()->timestamp;
        }

        // Check if there's already an active attendance for this date
        $existingAttendance = Attendance::where('user_id', $userid)
            ->where('date', $date)
            ->whereNull('time_out')
            ->first();
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You are already checked in for this date!');
        }

        $attendance = Attendance::create([
            'user_id' => $userid,
            'date' => $date,
            'time_in' => $timein,
            'time_out' => null,
            'totalhours' => 0
        ]);

        return redirect()->back()->with('success', 'Clocked In!');
    }

    public function timeOut()
    {
        $user = Auth::user();
        $userid = $user->id;
        $timeout = now()->timestamp; // Use Carbon with timezone

        // Use the same date logic for consistency
        $currentHour = now()->hour;
        if ($currentHour >= 0 && $currentHour <= 6) {
            // Between midnight and 6 AM, use previous day
            $date = now()->subDay()->startOfDay()->timestamp;
        } else {
            $date = now()->startOfDay()->timestamp;
        }

        $attendance = Attendance::where('user_id', $userid)
            ->where('date', $date)
            ->whereNull('time_out')
            ->first();

        if ($attendance) {
            $attendance->update([
                'time_out' => $timeout,
                'totalhours' => $timeout - $attendance->time_in
            ]);

            return redirect()->back()->with('success', 'Clocked Out!');
        }

        return redirect()->back()->with('error', 'No active attendance found!');
    }

    public function datewise(Request $request)
    {
        // Get month and year from request, default to current month/year
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        // Calculate first and last day timestamps of the month
        $firstDay = strtotime("first day of $year-$month");
        $lastDay = strtotime("last day of $year-$month");

        $users = User::role('employee')->get();

        $attendanceData = [];
        $presentCount = 0;
        $halfDayCount = 0;
        $leaveCount = 0;

        // For each user, build an array of attendance for each day of the month
        foreach ($users as $user) {
            $userAttendance = [];
            for ($date = $firstDay; $date <= $lastDay; $date += 86400) {
                $perdayattendance = Attendance::where('date', $date)->where('user_id', $user->id)->first();
                $day = date('l', $date);

                if ($perdayattendance == null) {
                    if ($date > strtotime(date('d-M-Y'))) {
                        $statusData = ['status' => 'future', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Future'];
                    } else {
                        if (date('D', $date) == 'Sat' || date('D', $date) == 'Sun') {
                            $statusData = ['status' => 'weekend', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Weekend'];
                        } elseif ($date == strtotime(date('d-M-Y'))) {
                            $statusData = ['status' => 'today', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Today'];
                        } else {
                            $statusData = ['status' => 'absent', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Absent'];
                        }
                    }
                } elseif ($perdayattendance->date == strtotime(date('d-M-Y')) && $perdayattendance->time_out == null) {
                    $statusData = ['status' => 'today', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'name' => 'Today'];
                } elseif ($perdayattendance->totalhours >= 16200 && $perdayattendance->totalhours <= 21600) {
                    $statusData = ['status' => 'halfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'name' => 'Half Day'];
                    $halfDayCount++;
                } elseif ($perdayattendance->totalhours < 16200 && $perdayattendance->totalhours != null) {
                    $statusData = ['status' => 'nohalfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'name' => 'Less than Half Day (Absent)'];
                } elseif ($perdayattendance->time_out == null && $perdayattendance->time_in != null) {
                    $statusData = ['status' => 'active', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'name' => 'Active'];
                } else {
                    $statusData = ['status' => 'present', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out, 'totalhours' => $perdayattendance->totalhours, 'name' => 'Present'];
                    $presentCount++;

                    if ($statusData['timein'] != null) {
                        $timeIn = \Carbon\Carbon::createFromTimestamp($perdayattendance->time_in);

                        if ($user->shift) {
                            $shiftStartTime = \Carbon\Carbon::parse($user->shift->start_time);
                            $graceTime = $user->shift->grace_time ?? 0;
                            $shiftStartTimeWithGrace = $shiftStartTime->copy()->addMinutes($graceTime);

                            if ($timeIn->format('H:i:s') > $shiftStartTimeWithGrace->format('H:i:s')) {
                                $statusData['status'] = 'late';
                                $statusData['name'] = 'Late';
                            } elseif ($perdayattendance->totalhours < ($user->shift->shift_hours * 3600)) {
                                $statusData['status'] = 'early';
                                $statusData['name'] = 'Early Leave';
                            }
                        }
                    }
                }

                // Check if employee is on leave (you'll need to implement your leave logic)
                // This is a placeholder - adjust based on your leave system
                $isOnLeave = false; // Implement your leave check logic here
                if ($isOnLeave) {
                    $statusData = ['status' => 'leave', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'On Leave'];
                    $leaveCount++;
                }

                $userAttendance[] = [
                    'date' => $date,
                    'day' => $day,
                    'attendance' => $statusData
                ];
            }

            $attendanceData[] = [
                'user' => $user,
                'attendance' => $userAttendance
            ];
        }

        $totalEmployees = $users->count();

        return view('attendance.datewise', compact('attendanceData', 'month', 'year', 'totalEmployees', 'presentCount', 'halfDayCount', 'leaveCount'));
    }


    public function showAttendance($month = null, $year = null, $userid = null)
    {
        if ($month === null) {
            $month = now()->format('m');
        }
        if ($userid === null) {
            $userid = Auth::id();
        }
        if ($year === null) {
            $year = now()->format('Y');
        }

        $date = '01-' . $month . '-' . $year;
        $firstday = strtotime(date('Y-m-01', strtotime($date)));
        $lastday = strtotime(date('Y-m-t', strtotime($date)));

        $attendance = [];
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $user = User::find($userid);

        for ($i = $firstday; $i <= $lastday; $i += 86400) {
            $perdayattendance = Attendance::where([['user_id', '=', $userid], ['date', '=', $i]])->first();

            $day = date('l', $i);



            if ($perdayattendance == null) {
                if ($i > strtotime(date('d-M-Y'))) {
                    $data = ['status' => 'future', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => ''];
                } else {
                    if (date('D', $i) == 'Sat' || date('D', $i) == 'Sun') {
                        $data = ['status' => 'weekend', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => 'Weekend'];
                    } elseif ($i == strtotime(date('d-M-Y'))) {
                        $data = ['status' => 'today', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => 'Today'];
                    } else {
                        $data = ['status' => 'absent', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => 'Absent'];
                    }
                }
            } elseif ($perdayattendance->date == strtotime(date('d-M-Y')) && $perdayattendance->time_out == null) {
                // Show "--" for timeout when not checked out today
                $data = ['status' => 'today', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => 'Today'];
            } elseif ($perdayattendance->totalhours >= 16200 && $perdayattendance->totalhours <= 21600) {
                $data = ['status' => 'halfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'date' => $i, 'day' => $day, 'name' => 'Half Day'];
            } elseif ($perdayattendance->totalhours < 16200 && $perdayattendance->totalhours != null) {
                $data = ['status' => 'nohalfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'date' => $i, 'day' => $day, 'name' => 'Less than Half Day (Absent)'];
            } elseif ($perdayattendance->time_out == null && $perdayattendance->time_in != null) {
                // Show "--" for timeout when not checked out, regardless of time elapsed
                $data = ['status' => 'active', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'day' => $day, 'name' => 'Active'];
            } else {
                $data = ['status' => 'present', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out, 'totalhours' => $perdayattendance->totalhours, 'date' => $i, 'day' => $day, 'name' => 'Present'];

                if ($data['timein'] != null) {
                    $timeIn = \Carbon\Carbon::createFromTimestamp($perdayattendance->time_in);
                    $user = Auth::user();

                    if ($user->shift) {
                        $shiftStartTime = \Carbon\Carbon::parse($user->shift->start_time);
                        $graceTime = $user->shift->grace_time ?? 0;
                        $shiftStartTimeWithGrace = $shiftStartTime->copy()->addMinutes($graceTime);

                        if ($timeIn->format('H:i:s') > $shiftStartTimeWithGrace->format('H:i:s')) {
                            $data['status'] = 'late';
                        } elseif ($perdayattendance->totalhours < ($user->shift->shift_hours * 3600)) {
                            $data['status'] = 'early';
                        }
                    }
                }
            }

            array_push($attendance, $data);
        }

        $totalhours = array_sum(array_map(function ($item) {
            return is_numeric($item['totalhours']) ? (int) $item['totalhours'] : 0;
        }, $attendance));
        return view('attendance.show', compact('attendance', 'firstday', 'lastday', 'month', 'year', 'totalhours', 'user'));
    }

    public function generateCSV(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $date = '01-' . $month . '-' . $year;
        $firstday = strtotime(date('Y-m-01', strtotime($date)));
        $lastday = strtotime(date('Y-m-t', strtotime($date)));

        $users = User::role('employee')->get();
        $csvData = [];
        $header = ['Employee Name', 'Date', 'Day', 'Status', 'Time In', 'Time Out', 'Total Hours'];
        array_push($csvData, $header);

        foreach ($users as $user) {
            for ($i = $firstday; $i <= $lastday; $i += 86400) {
                $perdayattendance = Attendance::where([
                    ['user_id', '=', $user->id],
                    ['date', '=', $i]
                ])->first();

                $day = date('l', $i);
                $data = [];

                if ($perdayattendance == null) {
                    if ($i > strtotime(date('d-M-Y'))) {
                        $data = ['status' => 'future', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => ''];
                    } else {
                        if (date('D', $i) == 'Sat' || date('D', $i) == 'Sun') {
                            $data = ['status' => 'weekend', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Weekend'];
                        } elseif ($i == strtotime(date('d-M-Y'))) {
                            $data = ['status' => 'today', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Today'];
                        } else {
                            $data = ['status' => 'absent', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'name' => 'Absent'];
                        }
                    }
                } elseif ($perdayattendance->date == strtotime(date('d-M-Y')) && $perdayattendance->time_out == null) {
                    $data = ['status' => 'today', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'name' => 'Today'];
                } elseif ($perdayattendance->totalhours >= 16200 && $perdayattendance->totalhours <= 21600) {
                    $data = ['status' => 'halfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'name' => 'Half Day'];
                } elseif ($perdayattendance->totalhours < 16200 && $perdayattendance->totalhours != null) {
                    $data = ['status' => 'nohalfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'name' => 'Less than Half Day (Absent)'];
                } elseif ($perdayattendance->time_out == null && $perdayattendance->time_in != null) {
                    $data = ['status' => 'active', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'name' => 'Active'];
                } else {
                    $data = [
                        'status' => 'present',
                        'timein' => $perdayattendance->time_in,
                        'timeout' => $perdayattendance->time_out,
                        'totalhours' => $perdayattendance->totalhours,
                        'name' => 'Present'
                    ];

                    if ($data['timein'] != null) {
                        $timeIn = \Carbon\Carbon::createFromTimestamp($perdayattendance->time_in);

                        if ($user->shift) {
                            $shiftStartTime = \Carbon\Carbon::parse($user->shift->start_time);
                            $graceTime = $user->shift->grace_time ?? 0;
                            $shiftStartTimeWithGrace = $shiftStartTime->copy()->addMinutes($graceTime);

                            if ($timeIn->format('H:i:s') > $shiftStartTimeWithGrace->format('H:i:s')) {
                                $data['status'] = 'late';
                            } elseif ($perdayattendance->totalhours < ($user->shift->shift_hours * 3600)) {
                                $data['status'] = 'early';
                            }
                        }
                    }
                }

                // Format row for CSV
                $csvRow = [
                    $user->name,
                    date('Y-m-d', $i),
                    $day,
                    ucfirst($data['status']),
                    $data['timein'] != '-' ? date('H:i:s', $data['timein']) : '-',
                    $data['timeout'] != '-' ? date('H:i:s', $data['timeout']) : '-',
                    is_numeric($data['totalhours']) ? gmdate("H:i:s", $data['totalhours']) : $data['totalhours'],
                ];

                array_push($csvData, $csvRow);
            }
        }

        $filename = "attendance_{$month}_{$year}.csv";
        $handle = fopen($filename, 'w');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function liveCalendar(Request $request, $month = null, $year = null)
    {
        $month = $month ?? now()->format('m');
        $year = $year ?? now()->format('Y');

        $firstDay = strtotime(date('Y-m-01', strtotime('01-' . $month . '-' . $year)));
        $lastDay = strtotime(date('Y-m-t', strtotime('01-' . $month . '-' . $year)));

        $users = User::role('employee')->get();

        $allAttendance = [];

        foreach ($users as $user) {
            $attendanceByDay = [];
            
            for ($i = $firstDay; $i <= $lastDay; $i += 86400) {
                $perdayattendance = Attendance::where('user_id', $user->id)
                    ->where('date', $i)
                    ->first();

                $statusData = [];

                if ($perdayattendance == null) {
                    if ($i > strtotime(date('d-M-Y'))) {
                        $statusData = ['status' => 'future', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Future'];
                    } else {
                        if (date('D', $i) == 'Sat' || date('D', $i) == 'Sun') {
                            $statusData = ['status' => 'weekend', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Weekend'];
                        } elseif ($i == strtotime(date('d-M-Y'))) {
                            $statusData = ['status' => 'today', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Today'];
                        } else {
                            $statusData = ['status' => 'absent', 'timein' => '-', 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Absent'];
                        }
                    }
                } elseif ($perdayattendance->date == strtotime(date('d-M-Y')) && $perdayattendance->time_out == null) {
                    $statusData = ['status' => 'today', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Today'];
                } elseif ($perdayattendance->totalhours >= 16200 && $perdayattendance->totalhours <= 21600) {
                    $statusData = ['status' => 'halfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'date' => $i, 'name' => 'Half Day'];
                } elseif ($perdayattendance->totalhours < 16200 && $perdayattendance->totalhours != null) {
                    $statusData = ['status' => 'nohalfday', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out ?? '-', 'totalhours' => $perdayattendance->totalhours ?? '-', 'date' => $i, 'name' => 'Less than Half Day (Absent)'];
                } elseif ($perdayattendance->time_out == null && $perdayattendance->time_in != null) {
                    $statusData = ['status' => 'active', 'timein' => $perdayattendance->time_in, 'timeout' => '-', 'totalhours' => '-', 'date' => $i, 'name' => 'Active'];
                } else {
                    $statusData = ['status' => 'present', 'timein' => $perdayattendance->time_in, 'timeout' => $perdayattendance->time_out, 'totalhours' => $perdayattendance->totalhours, 'date' => $i, 'name' => 'Present'];

                    if ($statusData['timein'] != null) {
                        $timeIn = \Carbon\Carbon::createFromTimestamp($perdayattendance->time_in);

                        if ($user->shift) {
                            $shiftStartTime = \Carbon\Carbon::parse($user->shift->start_time);
                            $graceTime = $user->shift->grace_time ?? 0;
                            $shiftStartTimeWithGrace = $shiftStartTime->copy()->addMinutes($graceTime);

                            if ($timeIn->format('H:i:s') > $shiftStartTimeWithGrace->format('H:i:s')) {
                                $statusData['status'] = 'late';
                                $statusData['name'] = 'Late';
                            } elseif ($perdayattendance->totalhours < ($user->shift->shift_hours * 3600)) {
                                $statusData['status'] = 'early';
                                $statusData['name'] = 'Early Leave';
                            }
                        }
                    }
                }

                $attendanceByDay[date('j', $i)] = $statusData;
            }

            $allAttendance[] = [
                'user' => $user,
                'days' => $attendanceByDay,
            ];
        }

        $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return view('attendance.live', [
            'month' => $month,
            'year' => $year,
            'allAttendance' => $allAttendance,
            'totalDaysInMonth' => $totalDaysInMonth,
        ]);
    }
}
