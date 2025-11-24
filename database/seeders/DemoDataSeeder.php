<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\Invoice;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users for each role
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@zamzam.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        $salesManager = User::create([
            'name' => 'John Manager',
            'email' => 'manager@zamzam.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'is_active' => true,
        ]);
        $salesManager->assignRole('Sales Manager');

        $salesAgent1 = User::create([
            'name' => 'Sarah Agent',
            'email' => 'agent1@zamzam.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
            'is_active' => true,
        ]);
        $salesAgent1->assignRole('Sales Agent');

        $salesAgent2 = User::create([
            'name' => 'Mike Agent',
            'email' => 'agent2@zamzam.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567893',
            'is_active' => true,
        ]);
        $salesAgent2->assignRole('Sales Agent');

        $hr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@zamzam.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567894',
            'is_active' => true,
        ]);
        $hr->assignRole('HR');

        // Create departments
        $salesDept = Department::create([
            'name' => 'Sales',
            'description' => 'Sales Department',
        ]);

        $hrDept = Department::create([
            'name' => 'Human Resources',
            'description' => 'HR Department',
        ]);

        // Create shifts
        $shift1 = Shift::create([
            'name' => 'Evening Shift',
            'start_time' => '19:00',
            'end_time' => '04:00',
            'grace_period_minutes' => 20,
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ]);

        $shift2 = Shift::create([
            'name' => 'Night Shift',
            'start_time' => '20:00',
            'end_time' => '05:00',
            'grace_period_minutes' => 20,
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ]);

        // Create employees (link sales agents)
        Employee::create([
            'user_id' => $salesAgent1->id,
            'employee_id' => 'EMP001',
            'department_id' => $salesDept->id,
            'shift_id' => $shift1->id,
            'designation' => 'Sales Agent',
            'joining_date' => now()->subMonths(6),
            'salary' => 3000.00,
        ]);

        Employee::create([
            'user_id' => $salesAgent2->id,
            'employee_id' => 'EMP002',
            'department_id' => $salesDept->id,
            'shift_id' => $shift2->id,
            'designation' => 'Sales Agent',
            'joining_date' => now()->subMonths(3),
            'salary' => 3000.00,
        ]);

        Employee::create([
            'user_id' => $hr->id,
            'employee_id' => 'EMP003',
            'department_id' => $hrDept->id,
            'shift_id' => $shift1->id,
            'designation' => 'HR Manager',
            'joining_date' => now()->subYear(),
            'salary' => 4000.00,
        ]);

        // Create demo vehicles
        $vehicles = [
            [
                'title' => '2023 Toyota Camry LE',
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2023,
                'condition' => 'Used',
                'availability' => 'Available',
            ],
            [
                'title' => '2024 Honda Accord Sport',
                'make' => 'Honda',
                'model' => 'Accord',
                'year' => 2024,
                'condition' => 'New',
                'availability' => 'Available',
            ],
            [
                'title' => '2022 BMW 3 Series',
                'make' => 'BMW',
                'model' => '3 Series',
                'year' => 2022,
                'condition' => 'Used',
                'availability' => 'Available',
            ],
            [
                'title' => '2023 Mercedes-Benz C-Class',
                'make' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year' => 2023,
                'condition' => 'Used',
                'availability' => 'Available',
            ],
            [
                'title' => '2024 Nissan Altima SV',
                'make' => 'Nissan',
                'model' => 'Altima',
                'year' => 2024,
                'condition' => 'New',
                'availability' => 'Available',
            ],
            [
                'title' => '2023 Ford Mustang GT',
                'make' => 'Ford',
                'model' => 'Mustang',
                'year' => 2023,
                'condition' => 'Used',
                'availability' => 'Reserved',
            ],
            [
                'title' => '2024 Chevrolet Malibu LT',
                'make' => 'Chevrolet',
                'model' => 'Malibu',
                'year' => 2024,
                'condition' => 'New',
                'availability' => 'Available',
            ],
            [
                'title' => '2022 Lexus ES 350',
                'make' => 'Lexus',
                'model' => 'ES',
                'year' => 2022,
                'condition' => 'Used',
                'availability' => 'Sold Out',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create(array_merge($vehicleData, [
                'steering_type' => 'LHD',
                'chassis_engine_no' => 'CH' . rand(100000, 999999),
                'body_type' => 'Sedan',
                'stock_id' => 'STK' . rand(1000, 9999),
                'transmission' => 'Automatic',
                'fuel_type' => 'Gasoline',
                'mileage' => rand(5000, 50000),
                'color' => ['White', 'Black', 'Silver', 'Blue', 'Red'][rand(0, 4)],
                'doors' => 4,
                'features' => 'Bluetooth, Backup Camera, Cruise Control',
                'safety_features' => 'ABS, Airbags, Traction Control',
                'price' => rand(20000, 50000),
                'created_by' => $salesManager->id,
            ]));
        }

        // Create demo customers
        $customer1User = User::create([
            'name' => 'James Smith',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1555000001',
            'is_active' => true,
        ]);
        $customer1User->assignRole('Customer');

        $customer1 = Customer::create([
            'user_id' => $customer1User->id,
            'phone' => '+1555000001',
            'address' => '123 Main St, City, State 12345',
            'lead_source' => 'Website',
            'status' => 'In Negotiation',
            'assigned_to' => $salesAgent1->id,
            'created_by' => $salesManager->id,
        ]);

        $customer2User = User::create([
            'name' => 'Emily Johnson',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1555000002',
            'is_active' => true,
        ]);
        $customer2User->assignRole('Customer');

        $customer2 = Customer::create([
            'user_id' => $customer2User->id,
            'phone' => '+1555000002',
            'address' => '456 Oak Ave, City, State 12345',
            'lead_source' => 'WhatsApp',
            'status' => 'Follow-up',
            'assigned_to' => $salesAgent2->id,
            'created_by' => $salesManager->id,
        ]);

        // Create demo invoices
        $vehicle1 = Vehicle::where('availability', 'Sold Out')->first();
        if ($vehicle1) {
            Invoice::create([
                'invoice_number' => 'INV-2025-00001',
                'customer_id' => $customer1->id,
                'vehicle_id' => $vehicle1->id,
                'vehicle_price' => 35000.00,
                'total_paid' => 35000.00,
                'remaining_balance' => 0,
                'status' => 'Paid',
                'created_by' => $salesAgent1->id,
            ]);
        }

        // Create announcement
        Announcement::create([
            'title' => 'Welcome to Zamzam CRM',
            'content' => 'This is your new automotive CRM system. Start managing your vehicle sales efficiently!',
            'created_by' => $superAdmin->id,
            'is_active' => true,
        ]);

        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('==================');
        $this->command->info('Super Admin: admin@zamzam.com / password');
        $this->command->info('Sales Manager: manager@zamzam.com / password');
        $this->command->info('Sales Agent 1: agent1@zamzam.com / password');
        $this->command->info('Sales Agent 2: agent2@zamzam.com / password');
        $this->command->info('HR: hr@zamzam.com / password');
        $this->command->info('Customer 1: customer1@example.com / password');
        $this->command->info('Customer 2: customer2@example.com / password');
    }
}
