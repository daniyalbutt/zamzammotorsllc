<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\{Role, Permission};
use Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:user|create user|edit user|delete user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = User::orderBy('id', 'desc');

        if (Auth::user()->hasPermissionTo('user') && Auth::user()->getRole() == 'admin') {
            $data = $data
                ->where('id', '!=', Auth::id())
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'admin');
                });
        } else if (Auth::user()->hasPermissionTo('show all customer')) {
            $data = $data->role('customer');
        } else if (Auth::user()->hasPermissionTo('assigned customer')) {
            $data = $data->where('created_by', Auth::user()->id);
        }

        if ($request->name != null) {
            $user_name = $request->name;
            $data = $data->where('name', 'like', '%' . $user_name . '%');
        }
        if ($request->email != null) {
            $user_email = $request->email;
            $data = $data->where('email', 'like', '%' . $user_email . '%');
        }
        $data = $data->get();
        return view('user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->getRole() == 'agent') {
            $roles = Role::where('name', 'customer')->get();
        } else {
            $roles = Role::all();
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
        
        return view('user.edit', compact('data', 'roles', 'permission', 'userPermissions'));
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
