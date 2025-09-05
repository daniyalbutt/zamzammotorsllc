<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



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
        return view('home', compact('data'));
    }
}
