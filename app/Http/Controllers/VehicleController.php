<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use App\Models\Forum;
use App\Models\Make;
use App\Models\Models;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shouldFilterByUser = Auth::user()->getRole() === 'agent' && !Auth::user()->hasPermissionTo('show all vehicles');
        $data = Vehicle::where('status', 1)
            ->when($shouldFilterByUser, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->when(Auth::user()->hasPermissionTo('assigned vehicles'), function ($query) {
                $query->whereHas('assigned_users', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('vehicle.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = null;
        $make = Make::all();
        $model = Models::all();
        $body_type = BodyType::all();
        return view('vehicle.create', compact('data', 'make', 'model', 'body_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        try {
            // Validate the request
            $request->validate([
                'title' => 'required|string|max:255',
                'condition' => 'nullable|string',
                'content' => 'nullable|string',
                'make_id' => 'nullable',
                'model_id' => 'nullable',
                'body_type_id' => 'nullable',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'offer_type' => 'nullable|string|max:255',
                'drive_type' => 'nullable|string',
                'transmission' => 'nullable|string',
                'fuel_type' => 'nullable|string',
                'cylinders' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'doors' => 'nullable|string|max:255',
                'features' => 'nullable|string',
                'safety_features' => 'nullable|string',
                'images' => 'nullable|array|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per image
                'status' => 'nullable|boolean',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);
            
            if(!is_numeric($request->make_id)){
                $make = Make::firstOrCreate(['name' => $request->make_id]);
                $request->merge(['make_id' => $make->id]);
            }

            if(!is_numeric($request->model_id)){
                $model = Models::firstOrCreate(['name' => $request->model_id, 'make_id' => $request->model_id]);
                $request->merge(['model_id' => $model->id]);
            }

            if(!is_numeric($request->body_type_id)){
                $bodyType = BodyType::firstOrCreate(['name' => $request->body_type_id]);
                $request->merge(['body_type_id' => $bodyType->id]);
            }

            // Create the vehicle
            $vehicle = new Vehicle();
            $vehicle->make_id = $request->make_id;
            $vehicle->model_id = $request->model_id;
            $vehicle->body_type_id = $request->body_type_id;
            $vehicle->title = $request->title;
            $vehicle->content = $request->content;
            $vehicle->condition = $request->condition ?? 'Used';
            $vehicle->offer_type = $request->offer_type;
            $vehicle->drive_type = $request->drive_type;
            $vehicle->transmission = $request->transmission;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->stock_id = $request->stock_id;
            $vehicle->availability = $request->availability;
            $vehicle->color = $request->color;
            $vehicle->doors = $request->doors;
            $vehicle->year = $request->year;
            $vehicle->rhd_lhd = $request->rhd_lhd;
            $vehicle->engine = $request->engine;
            $vehicle->mileage = $request->mileage;

            if ($request->features) {
                $decodedFeatures = json_decode($request->features, true);
                $updated = array_map(function ($item) {
                    return $item['value'] ?? null;
                }, array_filter($decodedFeatures));
                $vehicle->features = array_filter($updated);
            }



            // Handle safety features (convert comma-separated to JSON)
            if ($request->safety_features) {
                $decodedFeatures = json_decode($request->safety_features, true);
                $updated = array_map(function ($item) {
                    return $item['value'] ?? null;
                }, array_filter($decodedFeatures));
                $vehicle->safety_features = array_filter($updated);
            }

            // Handle image uploads and store paths as JSON
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $imagePaths[] = $path;
                }
            }
            $vehicle->image_paths = $imagePaths;
            
            // Handle video upload
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoPath = $video->store('vehicle_videos', 'public');
                $vehicle->video = $videoPath;
            }

            $vehicle->user_id = Auth::id();
            $vehicle->status = $request->has('status') ? 1 : 0;

            $vehicle->save();

            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle created successfully!',
                    'redirect' => route('vehicles.index')
                ]);
            }

            // Regular redirect for non-AJAX requests
            return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->validator->errors()
                ], 422);
            }

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Vehicle::find($id);
        return view('vehicle.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $make = Make::all();
        $model = Models::all();
        $body_type = BodyType::all();
        $data = Vehicle::find($id);
        $users = null;
        if (Auth::user()->getRole() == 'agent') {
            $users = User::orderBy('id', 'desc');
            if (Auth::user()->hasPermissionTo('show all customer')) {
                $users = $users->role('customer');
            } else if (Auth::user()->hasPermissionTo('assigned customer')) {
                $users = $users->whereHas('assignedCustomer', function ($q) {
                    $q->where('agent_id', Auth::id());
                });
            }
        }
        return view('vehicle.create', compact('data', 'make', 'model', 'body_type', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request.
            $request->validate([
                'title' => 'required|string|max:255',
                'condition' => 'nullable|string',
                'content' => 'nullable|string',
                'make_id' => 'nullable',
                'model_id' => 'nullable',
                'body_type_id' => 'nullable',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'offer_type' => 'nullable|string|max:255',
                'drive_type' => 'nullable|string',
                'transmission' => 'nullable|string',
                'fuel_type' => 'nullable|string',
                'cylinders' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'doors' => 'nullable|string|max:255',
                'features' => 'nullable|string',
                'safety_features' => 'nullable|string',
                'images' => 'nullable|array|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per image
                'status' => 'nullable|boolean',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            if(!is_numeric($request->make_id)){
                $make = Make::firstOrCreate(['name' => $request->make_id]);
                $request->merge(['make_id' => $make->id]);
            }

            if(!is_numeric($request->model_id)){
                $model = Models::firstOrCreate(['name' => $request->model_id]);
                $request->merge(['model_id' => $model->id]);
            }

            if(!is_numeric($request->body_type_id)){
                $bodyType = BodyType::firstOrCreate(['name' => $request->body_type_id]);
                $request->merge(['body_type_id' => $bodyType->id]);
            }

            // Find the vehicle
            $vehicle = Vehicle::findOrFail($id);

            $vehicle->make_id = $request->make_id;
            $vehicle->model_id = $request->model_id;
            $vehicle->body_type_id = $request->body_type_id;
            $vehicle->title = $request->title;
            $vehicle->content = $request->content;
            $vehicle->condition = $request->condition ?? 'Used';
            $vehicle->offer_type = $request->offer_type;
            $vehicle->drive_type = $request->drive_type;
            $vehicle->transmission = $request->transmission;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->availability = $request->availability;
            $vehicle->cylinders = $request->cylinders;
            $vehicle->color = $request->color;
            $vehicle->doors = $request->doors;
            $vehicle->year = $request->year;
            $vehicle->rhd_lhd = $request->rhd_lhd;
            $vehicle->engine = $request->engine;
            $vehicle->mileage = $request->mileage;
            $vehicle->stock_id = $request->stock_id;

            // Handle features (convert comma-separated to JSON)
            if ($request->features) {
                $decodedFeatures = json_decode($request->features, true);
                $updated = array_map(function ($item) {
                    return $item['value'] ?? null;
                }, array_filter($decodedFeatures));
                $vehicle->features = array_filter($updated);
            } else {
                $vehicle->features = null;
            }

            // Handle safety features (convert comma-separated to JSON)
            if ($request->safety_features) {
                $decodedFeatures = json_decode($request->safety_features, true);
                $updated = array_map(function ($item) {
                    return $item['value'] ?? null;
                }, array_filter($decodedFeatures));
                $vehicle->safety_features = array_filter($updated);
            } else {
                $vehicle->safety_features = null;
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                // Get existing image paths
                $existingPaths = $vehicle->image_paths;

                // Upload new images
                $newImagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $newImagePaths[] = $path;
                }

                // Combine existing and new image paths
                $allImagePaths = array_merge($existingPaths, $newImagePaths);

                // Limit to maximum 10 images
                if (count($allImagePaths) > 10) {
                    $allImagePaths = array_slice($allImagePaths, 0, 10);
                }

                $vehicle->image_paths = $allImagePaths;
            }
            
            // Handle video upload
            if ($request->hasFile('video')) {
                if ($vehicle->video && Storage::disk('public')->exists($vehicle->video)) {
                    Storage::disk('public')->delete($vehicle->video);
                }
                
                // Upload new video
                $video = $request->file('video');
                $videoPath = $video->store('vehicle_videos', 'public');
                $vehicle->video = $videoPath;
            }
            
            // Handle video removal
            if ($request->has('remove_video') && $request->remove_video) {
                if ($vehicle->video && Storage::disk('public')->exists($vehicle->video)) {
                    Storage::disk('public')->delete($vehicle->video);
                }
                $vehicle->video = null;
            }

            if ($request->user_id) {
                $vehicle->user_id = $request->user_id;
            }
            $vehicle->status = $request->has('status') ? 1 : 0;

            $vehicle->save();
            if ($request->has('assigned')) {
                if ($request->assigned == 'Not Assign') {
                    if ($vehicle->assigned_users()->exists()) {
                        $vehicle->assigned_users()->detach();
                    }
                } else {
                    $vehicle->assigned_users()->sync([
                        $request->assigned => ['assigned_by' => Auth::id()]
                    ]);
                }
                if ($request->assigned != 'Not Assign') {
                    $forum = Forum::updateOrCreate([
                        'vehicle_id' => $vehicle->id,
                        'agent_id' => Auth::id(),
                        'customer_id' => $request->assigned
                    ], [
                        'vehicle_id' => $vehicle->id,
                        'agent_id' => Auth::id(),
                        'customer_id' => $request->assigned
                    ]);

                    $forum->discussions()->create([
                        'user_id' => Auth::id(),
                        'content' => 'Forum created for vehicle: ' . $vehicle->title . ' (ID: ' . $vehicle->id . ') for agent: ' . Auth::user()->name . ' and customer: ' . ($vehicle->user ? User::find($request->assigned)->name : 'N/A')
                    ]);
                }
            }


            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle updated successfully!',
                    'redirect' => route('vehicles.index')
                ]);
            }

            // Regular redirect for non-AJAX requests
            return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->validator->errors()
                ], 422);
            }

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function removeImage(Request $request)
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|integer|exists:vehicles,id',
                'image_path' => 'required|string'
            ]);

            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $imagePaths = $vehicle->image_paths ? json_decode($vehicle->image_paths, true) : [];

            // Remove the specific image path
            $imagePaths = array_filter($imagePaths, function ($path) use ($request) {
                return $path !== $request->image_path;
            });

            // Delete the file from storage
            if (Storage::disk('public')->exists($request->image_path)) {
                Storage::disk('public')->delete($request->image_path);
            }

            // Update the vehicle with remaining image paths
            $vehicle->image_paths = json_encode(array_values($imagePaths)); // Re-index array
            $vehicle->save();

            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing image: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete video from a vehicle
     */
    public function deleteVideo(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'video_path' => 'required|string',
                'vehicle_id' => 'required|integer|exists:vehicles,id',
            ]);
            
            // Find the vehicle
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            
            // Check if the video path matches
            if ($vehicle->video != $request->video_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video path does not match the vehicle record'
                ], 400);
            }
            
            // Delete the video file
            if (Storage::disk('public')->exists($vehicle->video)) {
                Storage::disk('public')->delete($vehicle->video);
            }
            
            // Update the vehicle record
            $vehicle->video = null;
            $vehicle->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
