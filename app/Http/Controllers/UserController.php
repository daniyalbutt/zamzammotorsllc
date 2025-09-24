<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash};
use Spatie\Permission\Models\{Role, Permission};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = User::query()->orderBy('id', 'desc');

        // Admin with user permission - exclude themselves and other admins
        if (Auth::user()->hasPermissionTo('user') && Auth::user()->getRole() == 'admin') {
            $query->where('id', '!=', Auth::id())
                ->whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'));
        }

        // Show all customers
        if (Auth::user()->hasPermissionTo('show all customer')) {
            $query->whereHas('roles', fn($q) => $q->where('name', 'customer'));
        }

        // Show assigned customers (created by current user) /agent
        if (Auth::user()->hasPermissionTo('assigned customer')) {
            $query->orWhereHas('assignedCustomer', fn($q) => $q->where('agent_id', Auth::id()));
        }

        // Show assigned customers (additional condition) /sales manager
        if (Auth::user()->hasPermissionTo('show assigned customer')) {

            $query->orWhere(function ($q) {
                $q->whereHas('roles', fn($q) => $q->where('name', 'customer'))
                    ->where('created_by', Auth::id());
            });
        }



        // Show assigned agents
        if (Auth::user()->hasPermissionTo('show assigned agent')) {

            $query->orWhere(function ($q) {
                $q->whereHas('roles', fn($q) => $q->where('name', 'agent'))
                    ->where('created_by', Auth::id());
            });
        }


        $data = $query->paginate(10);
        return view('user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->getRole() == 'sales manager') {
            $roles = Role::where('name', 'customer')->orWhere('name', 'agent')->get();
        } else {
            $roles = Role::where('name', '!=', 'admin')->get();
        }
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required'
        ]);
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->created_by = Auth::user()->id;
        $data->save();
        $data->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        $roles = Role::where('name', '!=', 'admin')->get();
        $permission = Permission::all();
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        $agents = null;
        if (Auth::user()->hasRole('sales manager')) {
            $agents = User::role('agent')->where('created_by', Auth::id())->get();
            $roles = Role::whereIn('name', ['agent','customer'])->get();
        }
        return view('user.edit', compact('user', 'roles', 'permission', 'userPermissions', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);
        $data = $user;
        $data->name = $request->name;
        $data->email = $request->email;
        if ($request->password != null) {
            $data->password = Hash::make($request->password);
        }
        $data->save();
        if ($request->has('roles')) {
            $data->syncRoles($request->roles);
        }

        
        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'User updated successfully'
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
