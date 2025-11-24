<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users', 'create users', 'edit users', 'delete users',

            // Vehicle Management
            'view vehicles', 'create vehicles', 'edit vehicles', 'delete vehicles',

            // Customer Management
            'view customers', 'create customers', 'edit customers', 'delete customers', 'assign customers',

            // Invoice Management
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',

            // HR Management
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view shifts', 'create shifts', 'edit shifts', 'delete shifts',
            'view attendance', 'manage attendance',
            'view leaves', 'create leaves', 'approve leaves', 'reject leaves',
            'view payroll', 'create payroll', 'edit payroll',
            'view announcements', 'create announcements', 'edit announcements', 'delete announcements',

            // Reports
            'view sales reports', 'view hr reports',

            // Communication
            'view messages', 'send messages',

            // Activity Logs
            'view activity logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - Full access
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Sales Manager
        $salesManager = Role::create(['name' => 'Sales Manager']);
        $salesManager->givePermissionTo([
            'view vehicles', 'create vehicles', 'edit vehicles', 'delete vehicles',
            'view customers', 'create customers', 'edit customers', 'assign customers',
            'view invoices', 'view sales reports',
            'view messages', 'view activity logs',
        ]);

        // Sales Agent
        $salesAgent = Role::create(['name' => 'Sales Agent']);
        $salesAgent->givePermissionTo([
            'view vehicles',
            'view customers',
            'view invoices', 'create invoices', 'edit invoices',
            'view messages', 'send messages',
        ]);

        // HR
        $hr = Role::create(['name' => 'HR']);
        $hr->givePermissionTo([
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view shifts', 'create shifts', 'edit shifts', 'delete shifts',
            'view attendance', 'manage attendance',
            'view leaves', 'approve leaves', 'reject leaves',
            'view payroll', 'create payroll', 'edit payroll',
            'view announcements', 'create announcements', 'edit announcements', 'delete announcements',
            'view hr reports',
        ]);

        // Customer
        $customer = Role::create(['name' => 'Customer']);
        $customer->givePermissionTo([
            'view vehicles',
            'view invoices',
            'view messages', 'send messages',
        ]);
    }
}
