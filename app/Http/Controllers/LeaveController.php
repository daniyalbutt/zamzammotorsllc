<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function myLeaves()
    {
        
        return view('leaves.employeeleaves');
    }
}
