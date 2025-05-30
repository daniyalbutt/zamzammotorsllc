<?php

namespace App\Http\Controllers;

use App\Models\Models;
use Illuminate\Http\Request;
use Auth;

class ModelsController extends Controller
{

    function __construct(){
        $this->middleware('permission:model|create make|edit model|delete model', ['only' => ['index','show']]);
        $this->middleware('permission:create model', ['only' => ['create','store']]);
        $this->middleware('permission:edit model', ['only' => ['edit','update']]);
        $this->middleware('permission:delete model', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Models::where('status', 0)->orderBy('id', 'desc')->get();
        return view('model.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('model.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:models',
        ]);
        $data = new Models();
        $data->name = $request->name;
        $data->user_id = Auth::user()->id;
        $data->save();
        return redirect()->back()->with('success', 'Model Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Models $models)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Models::find($id);
        return view('model.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:makes,name,'.$id,
        ]);
        $data = Models::find($id);
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success', 'Model Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Models::find($id);
        $data->status = 1;
        $data->save();
        return redirect()->back()->with('success', 'Model Deleted Successfully');
    }
}
