<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MakeController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\BodyTypeController;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('auth');

Auth::routes(['register' => false]);
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('makes', MakeController::class);
    Route::resource('models', ModelsController::class);
    Route::resource('body-types', BodyTypeController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::post('vehicles/remove-image', [VehicleController::class, 'removeImage'])->name('vehicles.remove-image');
    Route::post('vehicles/upload-image', [VehicleController::class, 'removeImage'])->name('vehicles.upload-image');

    Route::middleware(['role:employee'])
        ->as('attendance.')
        ->group(function () {
            Route::post('/timein', [AttendanceController::class, 'timeIn'])->name('timeIn');
        });
});
