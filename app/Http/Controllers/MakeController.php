<?php

namespace App\Http\Controllers;

use App\Models\Make;
use Illuminate\Http\Request;
use Auth;

class MakeController extends Controller
{

    function __construct(){
        $this->middleware('permission:make|create make|edit make|delete make', ['only' => ['index','show']]);
        $this->middleware('permission:create make', ['only' => ['create','store']]);
        $this->middleware('permission:edit make', ['only' => ['edit','update']]);
        $this->middleware('permission:delete make', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Make::where('status', 1)->orderBy('id')->get();
        return view('make.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('make.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:makes',
        ]);
        $data = new Make();
        $data->name = $request->name;
        $data->user_id = Auth::user()->id;
        $data->save();
        return redirect()->back()->with('success', 'Make Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Make $make)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Make::find($id);
        return view('make.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:makes,name,'.$id,
        ]);
        $data = Make::find($id);
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success', 'Make Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Make::find($id);
        $data->status = 0;
        $data->save();
        return redirect()->back()->with('success', 'Make Deleted Successfully');
    }
}
