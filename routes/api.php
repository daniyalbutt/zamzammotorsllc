<?php

use App\Http\Controllers\Api\VehicleApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public API routes for vehicle listings (NO AUTHENTICATION REQUIRED)
| These endpoints are for the public website - NO PRICE DATA included
|
*/

// Public Vehicle API (No Authentication)
Route::prefix('vehicles')->group(function () {
    Route::get('/', [VehicleApiController::class, 'index']);
    Route::get('/filters', [VehicleApiController::class, 'filters']);
    Route::get('/{id}', [VehicleApiController::class, 'show']);
});
