<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Δημιουργία ρόλων αν δεν υπάρχουν
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Δημιουργία Superadmin χρήστη
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@exampl.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('111111'),
            ]
        );
        $superadmin->assignRole($superadminRole);

        // Δημιουργία απλού User
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('111111'),
            ]
        );
        $user->assignRole($userRole);

        // Δημιουργία ακόμα ενός χρήστη, πχ editor
$editor = User::firstOrCreate(
    ['email' => 'editor@example.com'],
    [
        'name' => 'Editor User',
        'password' => Hash::make('111111'),
    ]
);
$editor->assignRole($userRole);

    }
}
