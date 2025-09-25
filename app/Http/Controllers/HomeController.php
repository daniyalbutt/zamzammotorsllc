<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        if (Auth::user()->hasRole('hr')) {
            $data['totalEmployee'] = User::role('employee')->count();
            $data['totalPresent'] = Attendance::whereDate('date', strtotime(now()->toDateString()))->count();
            $data['totalLeave'] = Leave::whereDate('date', strtotime(now()->toDateString()))->count();
        }
        else if (Auth::user()->hasRole('customer')) {
            $data['carcount'] = Auth::user()->vehicles()->count();
            $data['forumcount'] = Auth::user()->customerForum()->count();
            $data['invoicecount'] = Auth::user()->invoice()->count();
        }
        else if(Auth::user()->hasRole('agent')){
            $data['carcount'] = Auth::user()->assignedVehicles()->count();
            $data['forumcount'] = Auth::user()->agentForum()->count();
            $data['invoicecount'] = Auth::user()->agentInvoice()->count();
        }
        else if(Auth::user()->hasRole('sales manager')){
            $data['agentcount'] = User::whereHas('roles', fn($q) => $q->where('name', 'agent'))->where('created_by', Auth::id())->count();
            $data['customercount'] = User::whereHas('roles', fn($q) => $q->where('name', 'customer'))->where('created_by', Auth::id())->count();
            $data['assignedcount'] = DB::table('assigned_vehicles')->count();
            
            
        }
        return view('home', compact('data'));
    }
}
