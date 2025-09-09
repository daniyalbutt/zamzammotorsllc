<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::role('customer')->with('assignedAgent')->get();
        return view('customers.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = null;
        $agents = User::role('agent')->get();
        return view('customers.create', compact('data', 'agents'));
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
            'meta.phone' => 'nullable|string|max:20',
            'meta.birthday' => 'nullable|date',
            'meta.address' => 'nullable|string|max:500',
            'meta.gender' => 'nullable|in:male,female,other',
            'meta.facebook' => 'nullable|url',
            'meta.linkedin' => 'nullable|url',
            'meta.twitter' => 'nullable|url',
            'meta.youtube' => 'nullable|url',
            'meta.instagram' => 'nullable|url',
            'meta.website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'assigned' => 'nullable|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads/customers', $imageName, 'public');
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_by' => Auth::id(),
                'image' => $imagePath
            ]);

            // Assign customer role
            $customerRole = Role::where('name', 'customer')->first();
            if ($customerRole) {
                $user->assignRole($customerRole);
            }

            // Store meta data
            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null && $value !== '') {
                        $user->setMeta($key, $value);
                    }
                }
            }

            // Handle agent assignment
            if ($request->assigned && $request->assigned !== 'Not Assign') {
                DB::table('assigned_agents')->insert([
                    'agent_id' => $request->assigned,
                    'customer_id' => $user->id,
                    'sales_manager_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error creating customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = User::role('customer')->with('assignedAgent')->findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::role('customer')->findOrFail($id);
        $agents = User::role('agent')->get();
        return view('customers.create', compact('data', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::role('customer')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'meta.phone' => 'nullable|string|max:20',
            'meta.birthday' => 'nullable|date',
            'meta.address' => 'nullable|string|max:500',
            'meta.gender' => 'nullable|in:male,female,other',
            'meta.facebook' => 'nullable|url',
            'meta.linkedin' => 'nullable|url',
            'meta.twitter' => 'nullable|url',
            'meta.youtube' => 'nullable|url',
            'meta.instagram' => 'nullable|url',
            'meta.website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'assigned' => 'nullable|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads/customers', $imageName, 'public');
                $user->image = $imagePath;
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            // Update meta data
            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    if ($value !== null && $value !== '') {
                        $user->setMeta($key, $value);
                    } else {
                        $user->removeMeta($key);
                    }
                }
            }

            // Handle agent assignment
            DB::table('assigned_agents')->where('customer_id', $user->id)->delete();
            
            if ($request->assigned && $request->assigned !== 'Not Assign') {
                DB::table('assigned_agents')->insert([
                    'agent_id' => $request->assigned,
                    'customer_id' => $user->id,
                    'sales_manager_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::role('customer')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Delete assigned agents relationships
            DB::table('assigned_agents')->where('customer_id', $user->id)->delete();

            // Delete user
            $user->delete();

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }
}
