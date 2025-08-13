<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'user']);
    }
}
// This seeder creates two roles: 'superadmin' and 'user'.