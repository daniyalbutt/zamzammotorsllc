<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function myLeaves()
    {
        $leaves = Leave::where('user_id', Auth::id())->get();

             
        return view('leaves.employeeleaves', compact('leaves'));
    }

    public function companyLeaves()
    {
        $leaves = Leave::with('user')->get();
        $emp_count = User::role('employee')->count();
        return view('leaves.companyleaves',compact('leaves','emp_count'));
    }

    public function changeLeaveStatus(Request $request){
        $request->validate([
            'status' => 'required'
        ]);

        $leave = Leave::find($request->id);
        $leave->status = $request->status;
        $leave->save();

        return redirect()->back()->with('success', 'Leave status changed');
        
    }

    public function applyLeave(Request $request){
        $request->validate([
            'leave_type' => 'required',
            'date' => 'required'
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'type' => $request->leave_type,
            'date' => strtotime($request->date),
            'status' => 'pending',
            'reason' => $request->input('reason')
        ]);

        return redirect()->back()->with('success', 'Leave applied successfully!');
        

    }
}
