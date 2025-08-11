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
        
        $date = '01-'.$month.'-'.$year;
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
             if (strtotime(date('d-M-Y')) == 1754852400) {
                dd($data);
            }
            
            array_push($attendance, $data);
        }
        
        $totalhours = array_sum(array_map(function ($item) {
            return is_numeric($item['totalhours']) ? (int) $item['totalhours'] : 0;
        }, $attendance));
        return view('attendance.show', compact('attendance', 'firstday', 'lastday', 'month', 'year', 'totalhours','user'));
   }
}
