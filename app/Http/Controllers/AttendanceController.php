<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
   public function timeIn()
   {
     $userid = Auth::user()->id;
        $shift = Auth::user()->getMeta('shift_timings');
        $timein = time();
        if (date('H:i', $timein) >= '00:00' && date('H:i', $timein) <= '06:00') {
            $date = strtotime(date('d-M-Y')) - 86400;
        } else {
            $date = strtotime(date('d-M-Y'));
        }
        $timein = Attendance::updateOrCreate([
            'user_id' => $userid,
            'date' => $date,
        ], [
            'timein' => $timein,
        ]);

        return redirect()->back()->with('success', $successmessage);
   }
}
