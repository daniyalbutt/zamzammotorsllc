<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleApiController extends Controller
{
    /**
     * Get all available vehicles for website (NO PRICE DATA)
     */
    public function index(Request $request)
    {
        $query = Vehicle::with('photos')
            ->select([
                'id',
                'title',
                'condition',
                'steering_type',
                'make',
                'model',
                'body_type',
                'stock_id',
                'year',
                'drive_type',
                'transmission',
                'fuel_type',
                'mileage',
                'color',
                'doors',
                'features',
                'safety_features',
                'availability',
                'video',
                'created_at',
                'updated_at',
            ]);

        // Only show available vehicles by default
        if (!$request->has('show_all')) {
            $query->where('availability', 'Available');
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

        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('drive_type')) {
            $query->where('drive_type', $request->drive_type);
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', "%{$request->color}%");
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['created_at', 'year', 'make', 'model', 'mileage'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->input('per_page', 12);
        $perPage = min($perPage, 50); // Max 50 per page

        $vehicles = $query->paginate($perPage);

        // Transform data to ensure NO price is included
        $vehicles->getCollection()->transform(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'title' => $vehicle->title,
                'condition' => $vehicle->condition,
                'steering_type' => $vehicle->steering_type,
                'make' => $vehicle->make,
                'model' => $vehicle->model,
                'body_type' => $vehicle->body_type,
                'stock_id' => $vehicle->stock_id,
                'year' => $vehicle->year,
                'drive_type' => $vehicle->drive_type,
                'transmission' => $vehicle->transmission,
                'fuel_type' => $vehicle->fuel_type,
                'mileage' => $vehicle->mileage,
                'color' => $vehicle->color,
                'doors' => $vehicle->doors,
                'features' => $vehicle->features ? explode(',', $vehicle->features) : [],
                'safety_features' => $vehicle->safety_features ? explode(',', $vehicle->safety_features) : [],
                'availability' => $vehicle->availability,
                'video' => $vehicle->video ? asset('storage/' . $vehicle->video) : null,
                'photos' => $vehicle->photos->map(function ($photo) {
                    return [
                        'url' => asset('storage/' . $photo->photo_path),
                        'order' => $photo->order,
                    ];
                }),
                'created_at' => $vehicle->created_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $vehicles->items(),
            'pagination' => [
                'total' => $vehicles->total(),
                'per_page' => $vehicles->perPage(),
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'from' => $vehicles->firstItem(),
                'to' => $vehicles->lastItem(),
            ],
        ]);
    }

    /**
     * Get single vehicle details (NO PRICE DATA)
     */
    public function show($id)
    {
        $vehicle = Vehicle::with('photos')->find($id);

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
            ], 404);
        }

        $data = [
            'id' => $vehicle->id,
            'title' => $vehicle->title,
            'condition' => $vehicle->condition,
            'steering_type' => $vehicle->steering_type,
            'chassis_engine_no' => $vehicle->chassis_engine_no,
            'make' => $vehicle->make,
            'model' => $vehicle->model,
            'body_type' => $vehicle->body_type,
            'stock_id' => $vehicle->stock_id,
            'year' => $vehicle->year,
            'offer_type' => $vehicle->offer_type,
            'drive_type' => $vehicle->drive_type,
            'transmission' => $vehicle->transmission,
            'fuel_type' => $vehicle->fuel_type,
            'mileage' => $vehicle->mileage,
            'color' => $vehicle->color,
            'doors' => $vehicle->doors,
            'features' => $vehicle->features ? explode(',', $vehicle->features) : [],
            'safety_features' => $vehicle->safety_features ? explode(',', $vehicle->safety_features) : [],
            'availability' => $vehicle->availability,
            'video' => $vehicle->video ? asset('storage/' . $vehicle->video) : null,
            'photos' => $vehicle->photos->map(function ($photo) {
                return [
                    'url' => asset('storage/' . $photo->photo_path),
                    'order' => $photo->order,
                ];
            }),
            'created_at' => $vehicle->created_at->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get available filter options
     */
    public function filters()
    {
        $makes = Vehicle::distinct()->pluck('make')->filter()->values();
        $years = Vehicle::distinct()->pluck('year')->filter()->sort()->values();
        $fuelTypes = Vehicle::distinct()->pluck('fuel_type')->filter()->values();
        $transmissions = Vehicle::distinct()->pluck('transmission')->filter()->values();
        $colors = Vehicle::distinct()->pluck('color')->filter()->values();

        return response()->json([
            'success' => true,
            'data' => [
                'makes' => $makes,
                'years' => $years,
                'fuel_types' => $fuelTypes,
                'transmissions' => $transmissions,
                'colors' => $colors,
                'conditions' => ['New', 'Used'],
                'drive_types' => ['AWD/4WD', 'FWD', 'RWD'],
            ],
        ]);
    }
}
