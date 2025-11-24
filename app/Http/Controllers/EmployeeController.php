<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Shift;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'shift'])->latest()->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        $departments = Department::all();
        $shifts = Shift::all();
        return view('employees.create', compact('users', 'departments', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'department_id' => 'required|exists:departments,id',
            'shift_id' => 'required|exists:shifts,id',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'shift']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $users = User::whereDoesntHave('employee')->orWhere('id', $employee->user_id)->get();
        $departments = Department::all();
        $shifts = Shift::all();
        return view('employees.edit', compact('employee', 'users', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id,' . $employee->id,
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'shift_id' => 'required|exists:shifts,id',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
