<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'admin']);

        $admin = Admin::query()->first();
        $admin->assignRole('admin');
    }
}
