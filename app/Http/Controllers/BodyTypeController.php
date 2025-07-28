<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use Illuminate\Http\Request;
use Auth;

class BodyTypeController extends Controller
{
    function __construct(){
        $this->middleware('permission:body type|create make|edit body type|delete body type', ['only' => ['index','show']]);
        $this->middleware('permission:create body type', ['only' => ['create','store']]);
        $this->middleware('permission:edit body type', ['only' => ['edit','update']]);
        $this->middleware('permission:delete body type', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BodyType::where('status', 1)->orderBy('id', 'desc')->get();
        return view('bodytype.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bodytype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:body_types',
        ]);
        $data = new BodyType();
        $data->name = $request->name;
        $data->user_id = Auth::user()->id;
        $data->save();
        return redirect()->back()->with('success', 'Body Type Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(BodyType $bodyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = BodyType::find($id);
        return view('bodytype.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:body_types,name,'.$id,
        ]);
        $data = BodyType::find($id);
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success', 'Body Type Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = BodyType::find($id);
        $data->status = 0;
        $data->save();
        return redirect()->back()->with('success', 'Body Type Deleted Successfully');
    }
}
