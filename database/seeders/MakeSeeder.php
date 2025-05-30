<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['make','create make', 'edit make', 'delete make'];
        foreach ($permissions as $value) {
            Permission::create([
                'name' => $value
            ]);
        }
        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo($permissions);
    }
}
