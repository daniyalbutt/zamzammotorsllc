<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerNoteController;
use App\Http\Controllers\CustomerPortalController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Role-based dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:Super Admin');

    Route::get('/sales-manager/dashboard', [DashboardController::class, 'salesManagerDashboard'])
        ->name('sales-manager.dashboard')
        ->middleware('role:Sales Manager');

    Route::get('/sales-agent/dashboard', [DashboardController::class, 'salesAgentDashboard'])
        ->name('sales-agent.dashboard')
        ->middleware('role:Sales Agent');

    Route::get('/hr/dashboard', [DashboardController::class, 'hrDashboard'])
        ->name('hr.dashboard')
        ->middleware('role:HR');

    Route::get('/customer/dashboard', [DashboardController::class, 'customerDashboard'])
        ->name('customer.dashboard')
        ->middleware('role:Customer');

    // Default dashboard (fallback)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('Sales Manager')) return redirect()->route('sales-manager.dashboard');
        if ($user->hasRole('Sales Agent')) return redirect()->route('sales-agent.dashboard');
        if ($user->hasRole('HR')) return redirect()->route('hr.dashboard');
        if ($user->hasRole('Customer')) return redirect()->route('customer.dashboard');
        abort(403);
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicle routes
    Route::resource('vehicles', VehicleController::class);
    Route::post('vehicles/{vehicle}/update-status', [VehicleController::class, 'updateStatus'])
        ->name('vehicles.update-status');

    // Customer routes
    Route::resource('customers', CustomerController::class)->except(['create', 'store']);

    // Only Sales Managers and Super Admins can create customers
    Route::middleware('role:Sales Manager|Super Admin')->group(function () {
        Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    });

    Route::post('customers/{customer}/assign-agent', [CustomerController::class, 'assignAgent'])
        ->name('customers.assign-agent');
    Route::post('customers/check-customer', [CustomerController::class, 'checkCustomer'])
        ->name('customers.check-customer');

    // Customer notes
    Route::post('customers/{customer}/notes', [CustomerNoteController::class, 'store'])
        ->name('customer-notes.store');
    Route::delete('customer-notes/{note}', [CustomerNoteController::class, 'destroy'])
        ->name('customer-notes.destroy');

    // Invoice routes
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/add-payment', [InvoiceController::class, 'addPayment'])
        ->name('invoices.add-payment');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])
        ->name('invoices.download');

    // Message routes
    Route::get('customers/{customer}/messages', [MessageController::class, 'index'])
        ->name('messages.index');
    Route::post('customers/{customer}/messages', [MessageController::class, 'store'])
        ->name('messages.store');

    // Customer Portal Routes
    Route::middleware('role:Customer')->prefix('customer-portal')->name('customer-portal.')->group(function () {
        // My Profile with tabs
        Route::get('my-profile', [CustomerPortalController::class, 'myProfile'])
            ->name('my-profile');
        Route::post('update-password', [CustomerPortalController::class, 'updatePassword'])
            ->name('update-password');
        Route::post('update-account-info', [CustomerPortalController::class, 'updateAccountInfo'])
            ->name('update-account-info');
        Route::post('update-contact-info', [CustomerPortalController::class, 'updateContactInfo'])
            ->name('update-contact-info');

        // My Account Info
        Route::get('my-account-info', [CustomerPortalController::class, 'myAccountInfo'])
            ->name('my-account-info');

        // My Consignee Details
        Route::get('my-consignee-details', [CustomerPortalController::class, 'myConsigneeDetails'])
            ->name('my-consignee-details');
        Route::post('update-consignee-details', [CustomerPortalController::class, 'updateConsigneeDetails'])
            ->name('update-consignee-details');

        // My Favorites
        Route::get('my-favorites', [CustomerPortalController::class, 'myFavorites'])
            ->name('my-favorites');
        Route::post('add-to-favorites/{vehicle}', [CustomerPortalController::class, 'addToFavorites'])
            ->name('add-to-favorites');
        Route::delete('remove-from-favorites/{vehicle}', [CustomerPortalController::class, 'removeFromFavorites'])
            ->name('remove-from-favorites');

        // Reserved Vehicles
        Route::get('reserved-vehicles', [CustomerPortalController::class, 'reservedVehicles'])
            ->name('reserved-vehicles');

        // Purchased Vehicles
        Route::get('purchased-vehicles', [CustomerPortalController::class, 'purchasedVehicles'])
            ->name('purchased-vehicles');
    });

    // HR Module - Employees
    Route::resource('employees', EmployeeController::class)
        ->middleware('permission:view employees');

    // HR Module - Departments
    Route::resource('departments', DepartmentController::class)
        ->middleware('permission:view departments');

    // HR Module - Shifts
    Route::resource('shifts', ShiftController::class)
        ->middleware('permission:view shifts');

    // HR Module - Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index')
        ->middleware('permission:view attendance');
    Route::post('attendance/mark', [AttendanceController::class, 'mark'])
        ->name('attendance.mark')
        ->middleware('permission:manage attendance');

    // HR Module - Leaves
    Route::resource('leaves', LeaveController::class)
        ->middleware('permission:view leaves');
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])
        ->name('leaves.approve')
        ->middleware('permission:approve leaves');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])
        ->name('leaves.reject')
        ->middleware('permission:reject leaves');

    // HR Module - Payroll
    Route::resource('payrolls', PayrollController::class)
        ->middleware('permission:view payroll');
    Route::post('payrolls/{payroll}/mark-paid', [PayrollController::class, 'markPaid'])
        ->name('payrolls.mark-paid')
        ->middleware('permission:edit payroll');
    Route::post('payrolls/bulk-generate', [PayrollController::class, 'bulkGenerate'])
        ->name('payrolls.bulk-generate')
        ->middleware('permission:edit payroll');

    // HR Module - Announcements
    Route::resource('announcements', AnnouncementController::class)
        ->middleware('permission:view announcements');

    // Reports
    Route::get('reports/sales', [ReportController::class, 'sales'])
        ->name('reports.sales')
        ->middleware('permission:view sales reports');
    Route::get('reports/hr', [ReportController::class, 'hr'])
        ->name('reports.hr')
        ->middleware('permission:view hr reports');
});

require __DIR__.'/auth.php';
