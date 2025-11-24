<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_period_minutes' => 'required|integer|min:0',
            'working_days' => 'required|array',
        ]);

        Shift::create($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift created!');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_period_minutes' => 'required|integer|min:0',
            'working_days' => 'required|array',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift updated!');
    }
}
