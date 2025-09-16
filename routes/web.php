<?php

use App\Http\Controllers\AnnoucementController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MakeController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\BodyTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\VehicleController;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

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

    Route::resource('customers', CustomerController::class);
    Route::resource('forums', ForumController::class);
    Route::put('upload', [ForumController::class, 'upload'])->name('forums.upload');




    Route::middleware(['role:employee|hr'])
        ->as('attendance.')
        ->group(function () {
            Route::post('/timein', [AttendanceController::class, 'timeIn'])->name('timeIn');
            Route::post('/timeout', [AttendanceController::class, 'timeOut'])->name('timeOut');


            Route::get('get-attendance/{month?}/{year?}/{userid?}', [AttendanceController::class, 'showAttendance'])->name('show');
            Route::get('attendance/company', [AttendanceController::class, 'datewise'])->name('datewise');
            Route::get('attendance/live/{month?}/{year?}', [AttendanceController::class, 'liveCalendar'])->name('live');

            Route::get('my-leaves',[LeaveController::class, 'myLeaves'])->name('myLeaves');
            Route::post('apply-leave',[LeaveController::class, 'applyLeave'])->name('applyLeave');

            // Test route for timezone verification
            Route::get('/test-timezone', function () {
                return response()->json([
                    'php_timezone' => date_default_timezone_get(),
                    'laravel_timezone' => config('app.timezone'),
                    'current_time' => now()->format('Y-m-d H:i:s T'),
                    'current_time_utc' => now()->utc()->format('Y-m-d H:i:s T'),
                    'karachi_time' => now()->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s T'),
                    'timestamp' => time(),
                    'formatted_timestamp' => date('Y-m-d H:i:s T', time())
                ]);
            });
        });

    Route::middleware(['role:hr'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('shifts', ShiftController::class);
        Route::post('generate-csv',[AttendanceController::class, 'generateCSV'])->name('generate.csv');
        Route::get('company-leaves', [LeaveController::class, 'companyLeaves'])->name('company.leaves');
        Route::post('change-leave-status', [LeaveController::class, 'changeLeaveStatus'])->name('leave.updateStatus');
        Route::resource('payroll', PayslipController::class);
        Route::resource('announcements', AnnoucementController::class);

    });
});
