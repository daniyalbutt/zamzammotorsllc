<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (Auth::user()->hasRole('customer')) {
            $data = Forum::where('customer_id', Auth::id())->get();
        }
        return view('forums.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Forum::findOrFail($id);
        return view('forums.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            DB::beginTransaction();
            $forum = Forum::findOrFail($id);
            $disscussion = $forum->discussions()->create([
                'user_id' => Auth::id(),
                'content' => $request->content ?? null,
            ]);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('forum_images', 'public');
                    $disscussion->media()->create([
                        'file_path' => $path,
                    ]);
                }
            }
            DB::commit();
            return back()->with('success','Forum created');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upload(Request $request)
    {
        dd($request->all());
    }
}
