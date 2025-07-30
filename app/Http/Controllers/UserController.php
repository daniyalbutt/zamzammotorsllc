<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\{Role, Permission};
use Hash;
use Auth, DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:user|create user|edit user|delete user', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit user', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete user', ['only' => ['destroy']]);
        // $this->middleware('permission:users');
    }

    public function index(Request $request)
    {

        $query = User::orderBy('id', 'desc');
        if (Auth::user()->hasRole('agent')) {
            $query->whereHas('assignedAgent', function ($q) {
                $q->where('agent_id', Auth::id());
            });
        }
        if (Auth::user()->hasPermissionTo('user') && Auth::user()->getRole() == 'admin') {
            $query->where('id', '!=', Auth::id())
                ->whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'));
        }

        // Show all customers
        if (Auth::user()->hasPermissionTo('show all customer')) {
            $query->orWhereHas('roles', fn($q) => $q->where('name', 'customer'));
        }
        if (Auth::user()->hasPermissionTo('show all agent')) {
            $query->orWhereHas('roles', fn($q) => $q->where('name', 'agent'));
        }


        // Show assigned customers (additional condition)
        if (Auth::user()->hasPermissionTo('show assigned customer')) {

            $query->orWhereHas('roles', fn($q) => $q->where('name', 'customer'))->where('created_by', Auth::id());
        }

        // Show assigned agents
        if (Auth::user()->hasPermissionTo('show assigned agent')) {
            $query->orWhereHas('roles', fn($q) => $q->where('name', 'agent'))->where('created_by', Auth::id());
        }



        // Search filters
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $data = $query->get();
        return view('user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('id', 'desc');
        if (Auth::user()->hasPermissionTo('create edit assigned customer')) {
            $roles = $roles->orWhere('name', 'customer');
        }
        if (Auth::user()->hasPermissionTo('create edit assigned agent')) {

            $roles = $roles->orWhere('name', 'agent');
        }
        $roles = $roles->get();



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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required',
        ]);
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->created_by = Auth::user()->id;
        $data->save();
        $data->assignRole($request->role);
        $data->syncPermissions($request->permission);
        return redirect()->back()->with('success', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        $roles = Role::all();



        $permission = Permission::get();
        $userPermissions = $data->getAllPermissions()->pluck('name')->toArray();

        $users = null;                     
        if (Auth::user()->hasRole('sales manager')) {
            $users = User::orderBy('id', 'desc');

            if (Auth::user()->hasPermissionTo('show all agent')) {
                $users = $users->orWhereHas('roles', fn($q) => $q->where('name', 'agent'));
            } else if (Auth::user()->hasPermissionTo('show assigned agent')) {
                $users = $users->orWhereHas('roles', fn($q) => $q->where('name', 'agent'))->where('created_by', Auth::id());
            }
        }

        return view('user.edit', compact('data', 'roles', 'permission', 'userPermissions', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        if ($request->password != null) {
            $data->password = Hash::make($request->password);
        }
        $data->save();
        $data->syncRoles($request->role);
        $data->syncPermissions($request->permission);
        if ($request->has('assigned')) {
            if ($request->assigned == 'Not Assign') {
                DB::table('assigned_agents')->where('customer_id', $data->id)->delete();
            } else {
                DB::table('assigned_agents')->updateOrInsert(
                    ['agent_id' => $request->assigned],
                    ['sales_manager_id' => Auth::id(), 'customer_id' => $data->id]
                );
            }
        }
        return redirect()->back()->with('success', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

    public function logoBrief($id)
    {
        $data = Client::find($id);
        return view('logo-brief.index', compact('data'));
    }
}
