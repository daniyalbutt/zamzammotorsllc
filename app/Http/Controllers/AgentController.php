<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Spatie\Permission\Models\Role;

class AgentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:sales manager');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only show agents created by the current sales manager
        $data = User::role('agent')
            ->where('created_by', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('agent.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agent.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $agent = new User();
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->password = Hash::make($request->password);
        $agent->created_by = Auth::id(); 
        $agent->save();
        
        $agent->assignRole('agent');

        return redirect()->route('agents.index')
            ->with('success', 'Agent created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $agent)
    {
        // Only show agents created by the current sales manager
        if ($agent->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only view agents created by you.');
        }

        return view('agent.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $agent)
    {
        // Only allow editing agents created by the current sales manager
        if ($agent->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only edit agents created by you.');
        }

        return view('agent.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $agent)
    {
        // Only allow updating agents created by the current sales manager
        if ($agent->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only update agents created by you.');
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $agent->name = $request->name;
        $agent->email = $request->email;
        
        // Only update password if provided
        if ($request->filled('password')) {
            $agent->password = Hash::make($request->password);
        }
        
        $agent->save();

        return redirect()->route('agents.index')
            ->with('success', 'Agent updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $agent)
    {
        // Only allow deleting agents created by the current sales manager
        if ($agent->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only delete agents created by you.');
        }

        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent deleted successfully');
    }
}
