<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::role('employee')->get();
        
        return view('employees.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = null;
        $shifts = Shift::all();
        $departments = Department::all();
        return view('employees.create', compact('data','shifts', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'meta.designation' => 'required|string|max:255',
            'meta.department_id' => 'required|exists:departments,id',
            'meta.salary' => 'required|numeric|min:0',
            'meta.shift_id' => 'required|exists:shifts,id',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->created_by = auth()->id();
            $user->save();

            $user->assignRole('employee');

            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null) {
                        $user->setMeta($key, $value);
                    }
                }
            }

            return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        $shifts = Shift::all();
        $departments = Department::all();

        return view('employees.create', compact('data','shifts','departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'meta.designation' => 'required|string|max:255',
            'meta.department_id' => 'required|exists:departments,id',
            'meta.salary' => 'required|numeric|min:0',
            'meta.shift_id' => 'required|exists:shifts,id',
        ]);

        try {
            $user = User::findOrFail($id);
            
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Update password if provided
            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8']);
                $user->password = bcrypt($request->password);
            }
            
            $user->save();

            // Update meta data
            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null) {
                        $user->setMeta($key, $value);
                    }
                }
            }


            return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
           
            return back()->withInput()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
    }
}
