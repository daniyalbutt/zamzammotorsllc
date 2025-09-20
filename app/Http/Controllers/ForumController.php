<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Invoice;
use App\Models\VehiclesPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (Auth::user()->hasRole('customer')) {
            $data = Forum::where('customer_id', Auth::id())->latest()->get();
        } else if (Auth::user()->hasRole('agent')) {
            $data = Forum::where('agent_id', Auth::id())->latest()->get();
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
    public function store(Request $request) {}

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

        $request->validate([
            'content' => 'required_without:uploaded_files',
            'uploaded_files' => 'required_without:content',
        ]);
        try {
            DB::beginTransaction();
            $forum = Forum::findOrFail($id);
            $discussion = $forum->discussions()->create([
                'user_id' => Auth::id(),
                'content' => $request->content ?? null,
            ]);

            if ($request->has('uploaded_files') && !empty($request->uploaded_files)) {
                $uploadedFiles = json_decode($request->uploaded_files, true);
                if (is_array($uploadedFiles)) {
                    foreach ($uploadedFiles as $fileData) {
                        $discussion->media()->create([
                            'file_path' => $fileData['file_path'],
                            'file_name' => $fileData['file_name'],
                            'file_extension' => pathinfo($fileData['file_name'], PATHINFO_EXTENSION)
                        ]);
                    }
                }
            }

            // Handle regular file uploads (fallback)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('forum_images', 'public');
                    $discussion->media()->create([
                        'file_path' => $path,
                        'file_name' => $image->getClientOriginalName(),
                        'file_extension' => $image->getClientOriginalExtension()
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Forum discussion created successfully');
        } catch (\Exception $e) {
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
        try {
            // Validate request
            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No file provided'
                ], 400);
            }

            $chunkNumber = (int) $request->input('chunk', 0);
            $totalChunks = (int) $request->input('chunks', 1);
            $fileName = $request->input('name');
            $fileSize = (int) $request->input('size', 0);
            $uploadId = $request->input('uploadId', uniqid());

            // REMOVE the 100MB validation here since we're handling chunked uploads
            // The chunked upload approach allows files larger than 100MB

            // Create temporary directory for chunks
            $tempDir = storage_path('app/temp/chunks/' . $uploadId);
            if (!file_exists($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Failed to create temporary directory'
                    ], 500);
                }
            }

            // Save the chunk
            $file = $request->file('file');
            $chunkFile = $tempDir . '/' . $chunkNumber;

            if (!$file->move($tempDir, $chunkNumber)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to save chunk'
                ], 500);
            }

            // Check if all chunks are uploaded
            if ($chunkNumber == $totalChunks - 1) {
                // Combine all chunks
                $finalFile = storage_path('app/temp/' . $fileName);
                $handle = fopen($finalFile, 'w');

                if (!$handle) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Failed to create final file'
                    ], 500);
                }

                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $tempDir . '/' . $i;
                    if (file_exists($chunkPath)) {
                        $chunk = fopen($chunkPath, 'r');
                        if ($chunk) {
                            stream_copy_to_stream($chunk, $handle);
                            fclose($chunk);
                        }
                        unlink($chunkPath);
                    }
                }
                fclose($handle);

                // Remove temp directory
                rmdir($tempDir);

                // Move to final location
                $storedPath = Storage::disk('public')->putFile('forum_images', new \Illuminate\Http\File($finalFile));

                // Clean up temp file
                if (file_exists($finalFile)) {
                    unlink($finalFile);
                }

                return response()->json([
                    'success' => true,
                    'file_path' => $storedPath,
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'uploadId' => $uploadId
                ]);
            }

            return response()->json([
                'success' => true,
                'chunk' => $chunkNumber,
                'totalChunks' => $totalChunks,
                'uploadId' => $uploadId
            ]);
        } catch (\Exception $e) {
            Log::error('Chunked upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDirect(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:102400', // 100MB max for direct uploads
            ]);

            $file = $request->file('file');
            $path = $file->store('forum_images', 'public');

            return response()->json([
                'success' => true,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateAjax(Request $request, string $id)
    {
        try {

            DB::beginTransaction();
            $forum = Forum::findOrFail($id);
            $discussion = $forum->discussions()->create([
                'user_id' => Auth::id(),
                'content' => $request->content ?? null,
            ]);

            // Handle uploaded files from chunked upload
            if ($request->has('uploaded_files')) {
                $uploadedFiles = json_decode($request->uploaded_files, true);
                foreach ($uploadedFiles as $fileData) {
                    $discussion->media()->create([
                        'file_path' => $fileData['file_path'],
                        'file_name' => $fileData['file_name'],
                        'file_extension' => pathinfo($fileData['file_name'], PATHINFO_EXTENSION)
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Forum discussion created successfully',
                'discussion' => $discussion->load('user', 'media')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addDiscussion(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required_without:images',
            'images' => 'required_without:content',
        ]);
        try {
            DB::beginTransaction();
            $forum = Forum::findOrFail($id);
            $discussion = $forum->discussions()->create([
                'user_id' => Auth::id(),
                'content' => $request->content ?? null,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('forum_images', 'public');
                    $discussion->media()->create([
                        'file_path' => $path,
                        'file_name' => $image->getClientOriginalName(),
                        'file_extension' => $image->getClientOriginalExtension()
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Forum discussion created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function payInvoice(Request $request)
    {
        $request->validate([
            'car' => 'required',
            'forum_id' => 'required',
            'price' => 'required',
            'amount_date' => 'required|date',
            'agent_id' => 'required'
        ]);
        
        $invoice = new Invoice;
        $invoice->agent_id = $request->agent_id;
        $invoice->customer_id = $request->customer_id;
        $invoice->vehicle_id = $request->car;
        $invoice->forum_id = $request->forum_id;
        $invoice->amount = $request->price;
        $invoice->amount_date = $request->amount_date;
        $invoice->save();

        return back()->with('success', 'Invoice created successfully');

    }

    public function updateCustomerCarPrice(Request $request)
    {
        $request->validate([
            'car' => 'required',
            'forum_id' => 'required',
            'price' => 'required'
        ]);
        $forum = Forum::find($request->forum_id);
        VehiclesPrice::updateOrCreate([
            'forum_id' => $forum->id,
            'vehicle_id' => $request->car,
            'agent_id' => $request->agent_id,
            'customer_id' => $request->customer_id
        ], [
            'price' => $request->price
        ]);

        $forum->discussions()->create([
            'user_id' => Auth::id(),
            'content' => '<p>Your price is booked for $'.$request->price.'.'
        ]);
        
        return redirect()->back()->with('success','Your price is updated');
    }
}
