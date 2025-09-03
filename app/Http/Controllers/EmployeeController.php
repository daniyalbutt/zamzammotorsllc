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
        return view('employees.create', compact('data', 'shifts', 'departments'));
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

            // Personal Information validation
            'meta.phone' => 'nullable|string|max:20',
            'meta.birthday' => 'nullable|date',
            'meta.address' => 'nullable|string|max:500',
            'meta.gender' => 'nullable|in:male,female,other',
            'meta.date_of_joining' => 'nullable|date',

            // Emergency Contact validation
            'meta.primary_contact_name' => 'nullable|string|max:255',
            'meta.primary_contact_relationship' => 'nullable|string|max:100',
            'meta.primary_contact_phone' => 'nullable|string|max:20',
            'meta.primary_contact_email' => 'nullable|email',
            'meta.primary_contact_address' => 'nullable|string|max:500',

            'meta.secondary_contact_name' => 'nullable|string|max:255',
            'meta.secondary_contact_relationship' => 'nullable|string|max:100',
            'meta.secondary_contact_phone' => 'nullable|string|max:20',
            'meta.secondary_contact_email' => 'nullable|email',
            'meta.secondary_contact_address' => 'nullable|string|max:500',

            // Education validation
            'meta.education' => 'nullable|array',
            'meta.education.*.institute' => 'nullable|string|max:255',
            'meta.education.*.degree' => 'nullable|string|max:255',
            'meta.education.*.start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'meta.education.*.end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),

            // Experience validation
            'meta.experience' => 'nullable|array',
            'meta.experience.*.company' => 'nullable|string|max:255',
            'meta.experience.*.position' => 'nullable|string|max:255',
            'meta.experience.*.start_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'meta.experience.*.end_year' => 'nullable|integer|min:1900|max:' . date('Y'),

            // Bank Account validation
            'meta.account_holder_name' => 'nullable|string|max:255',
            'meta.account_number' => 'nullable|string|max:50',
            'meta.bank_name' => 'nullable|string|max:255',
            'meta.branch_name' => 'nullable|string|max:255',
            'meta.swift_code' => 'nullable|string|max:20',

            // Passport Information validation
            'meta.passport_number' => 'nullable|string|max:50',
            'meta.nationality' => 'nullable|string|max:100',
            'meta.passport_issue_date' => 'nullable|date',
            'meta.passport_expiry_date' => 'nullable|date|after:meta.passport_issue_date',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->created_by = auth()->id();
            $user->save();

            $user->assignRole('employee');

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        
                $request->image->move(public_path('uploads/user'), $imageName);

                $user->image = 'uploads/user/'.$imageName;
            }
            // Save meta data
            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null && $value !== '') {
                        // Handle arrays (education, experience) by JSON encoding
                        if (is_array($value)) {
                            // Filter out empty entries
                            $filteredArray = array_filter($value, function ($item) {
                                if (!is_array($item)) return !empty($item);
                                return !empty(array_filter($item, function ($val) {
                                    return !empty($val);
                                }));
                            });

                            if (!empty($filteredArray)) {
                                $user->setMeta($key, json_encode(array_values($filteredArray)));
                            }
                        } else {
                            $user->setMeta($key, $value);
                        }
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
        $data = User::find($id);
        return view('employees.show', compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        $shifts = Shift::all();
        $departments = Department::all();

        return view('employees.create', compact('data', 'shifts', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'meta.designation' => 'required|string|max:255',
            'meta.department_id' => 'required|exists:departments,id',
            'meta.salary' => 'required|numeric|min:0',
            'meta.shift_id' => 'required|exists:shifts,id',

            // Personal Information validation
            'meta.phone' => 'nullable|string|max:20',
            'meta.birthday' => 'nullable|date',
            'meta.address' => 'nullable|string|max:500',
            'meta.gender' => 'nullable|in:male,female,other',
            'meta.date_of_joining' => 'nullable|date',

            // Emergency Contact validation
            'meta.primary_contact_name' => 'nullable|string|max:255',
            'meta.primary_contact_relationship' => 'nullable|string|max:100',
            'meta.primary_contact_phone' => 'nullable|string|max:20',
            'meta.primary_contact_email' => 'nullable|email',
            'meta.primary_contact_address' => 'nullable|string|max:500',

            'meta.secondary_contact_name' => 'nullable|string|max:255',
            'meta.secondary_contact_relationship' => 'nullable|string|max:100',
            'meta.secondary_contact_phone' => 'nullable|string|max:20',
            'meta.secondary_contact_email' => 'nullable|email',
            'meta.secondary_contact_address' => 'nullable|string|max:500',

            // Education validation
            'meta.education' => 'nullable|array',
            'meta.education.*.institute' => 'nullable|string|max:255',
            'meta.education.*.degree' => 'nullable|string|max:255',
            'meta.education.*.start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'meta.education.*.end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),

            // Experience validation
            'meta.experience' => 'nullable|array',
            'meta.experience.*.company' => 'nullable|string|max:255',
            'meta.experience.*.position' => 'nullable|string|max:255',
            'meta.experience.*.start_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'meta.experience.*.end_year' => 'nullable|integer|min:1900|max:' . date('Y'),

            // Bank Account validation
            'meta.account_holder_name' => 'nullable|string|max:255',
            'meta.account_number' => 'nullable|string|max:50',
            'meta.bank_name' => 'nullable|string|max:255',
            'meta.branch_name' => 'nullable|string|max:255',
            'meta.swift_code' => 'nullable|string|max:20',

            // Passport Information validation
            'meta.passport_number' => 'nullable|string|max:50',
            'meta.nationality' => 'nullable|string|max:100',
            'meta.passport_issue_date' => 'nullable|date',
            'meta.passport_expiry_date' => 'nullable|date|after:meta.passport_issue_date',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        
                $request->image->move(public_path('uploads/user'), $imageName);

                $user->image = 'uploads/user/'.$imageName;
            }

            $user->save();

            // Update meta data
            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null && $value !== '') {
                        // Handle arrays (education, experience) by JSON encoding
                        if (is_array($value)) {
                            // Filter out empty entries
                            $filteredArray = array_filter($value, function ($item) {
                                if (!is_array($item)) return !empty($item);
                                return !empty(array_filter($item, function ($val) {
                                    return !empty($val);
                                }));
                            });

                            if (!empty($filteredArray)) {
                                $user->setMeta($key, json_encode(array_values($filteredArray)));
                            } else {
                                // Clear the meta if array is empty
                                $user->setMeta($key, null);
                            }
                        } else {
                            $user->setMeta($key, $value);
                        }
                    } else {
                        // Clear the meta if value is null or empty
                        $user->setMeta($key, null);
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
