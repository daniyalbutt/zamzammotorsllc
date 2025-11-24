<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class VehicleController extends Controller
{

    public function index(Request $request)
    {
        $query = Vehicle::with(['creator', 'photos']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('stock_id', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('make')) {
            $query->where('make', $request->make);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        $vehicles = $query->latest()->paginate(15);

        // Get unique values for filters
        $makes = Vehicle::distinct()->pluck('make');
        $years = Vehicle::distinct()->orderBy('year', 'desc')->pluck('year');

        return view('vehicles.index', compact('vehicles', 'makes', 'years'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'condition' => 'required|in:New,Used',
            'steering_type' => 'required|in:RHD,LHD',
            'chassis_engine_no' => 'nullable|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'stock_id' => 'required|string|unique:vehicles,stock_id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'offer_type' => 'nullable|string|max:255',
            'drive_type' => 'nullable|in:AWD/4WD,FWD,RWD',
            'transmission' => 'required|in:Automatic,Manual,CVT,Semi Automatic',
            'fuel_type' => 'required|in:Diesel,Gasoline,Hybrid,Electric',
            'mileage' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:255',
            'doors' => 'nullable|integer|min:2|max:7',
            'features' => 'nullable|string',
            'safety_features' => 'nullable|string',
            'availability' => 'required|in:Available,Reserved',
            'price' => 'nullable|numeric|min:0',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $validated['created_by'] = Auth::id();

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . Str::random(10) . '.' . $video->getClientOriginalExtension();
            $validated['video'] = $video->storeAs('vehicles/videos', $videoName, 'public');
        }

        $vehicle = Vehicle::create($validated);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('vehicles/photos', $photoName, 'public');

                VehiclePhoto::create([
                    'vehicle_id' => $vehicle->id,
                    'photo_path' => $photoPath,
                    'order' => $index,
                ]);
            }
        }

        // Log activity
        ActivityLog::create([
            'action' => 'created',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => 'Vehicle "' . $vehicle->title . '" was created',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully!');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['creator', 'photos', 'invoices.customer.user']);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicle->load('photos');
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'condition' => 'required|in:New,Used',
            'steering_type' => 'required|in:RHD,LHD',
            'chassis_engine_no' => 'nullable|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'stock_id' => 'required|string|unique:vehicles,stock_id,' . $vehicle->id,
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'offer_type' => 'nullable|string|max:255',
            'drive_type' => 'nullable|in:AWD/4WD,FWD,RWD',
            'transmission' => 'required|in:Automatic,Manual,CVT,Semi Automatic',
            'fuel_type' => 'required|in:Diesel,Gasoline,Hybrid,Electric',
            'mileage' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:255',
            'doors' => 'nullable|integer|min:2|max:7',
            'features' => 'nullable|string',
            'safety_features' => 'nullable|string',
            'availability' => 'required|in:Available,Reserved,Sold Out',
            'price' => 'nullable|numeric|min:0',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'delete_photos' => 'nullable|array',
        ]);

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video
            if ($vehicle->video) {
                Storage::disk('public')->delete($vehicle->video);
            }

            $video = $request->file('video');
            $videoName = time() . '_' . Str::random(10) . '.' . $video->getClientOriginalExtension();
            $validated['video'] = $video->storeAs('vehicles/videos', $videoName, 'public');
        }

        $vehicle->update($validated);

        // Delete selected photos
        if ($request->filled('delete_photos')) {
            $photosToDelete = VehiclePhoto::whereIn('id', $request->delete_photos)->get();
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
                $photo->delete();
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            $currentMaxOrder = $vehicle->photos()->max('order') ?? -1;
            foreach ($request->file('photos') as $index => $photo) {
                $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('vehicles/photos', $photoName, 'public');

                VehiclePhoto::create([
                    'vehicle_id' => $vehicle->id,
                    'photo_path' => $photoPath,
                    'order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        // Log activity
        ActivityLog::create([
            'action' => 'updated',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => 'Vehicle "' . $vehicle->title . '" was updated',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        // Delete associated files
        if ($vehicle->video) {
            Storage::disk('public')->delete($vehicle->video);
        }

        foreach ($vehicle->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        // Log activity
        ActivityLog::create([
            'action' => 'deleted',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => 'Vehicle "' . $vehicle->title . '" was deleted',
            'user_id' => Auth::id(),
        ]);

        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }

    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'availability' => 'required|in:Available,Reserved,Sold Out',
        ]);

        $oldStatus = $vehicle->availability;
        $vehicle->update(['availability' => $request->availability]);

        // Log activity
        ActivityLog::create([
            'action' => 'status_updated',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => 'Vehicle "' . $vehicle->title . '" status changed from ' . $oldStatus . ' to ' . $request->availability,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Vehicle status updated successfully!');
    }
}
