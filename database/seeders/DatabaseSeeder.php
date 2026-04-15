<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator']
        );

        $organiserRole = Role::firstOrCreate(
            ['name' => 'Organiser'],
            ['description' => 'Event Organiser']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'User'],
            ['description' => 'Regular User']
        );

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );

        // Create sample organiser
        User::firstOrCreate(
            ['email' => 'organiser@example.com'],
            [
                'name' => 'Organiser User',
                'password' => Hash::make('password'),
                'phone' => '0987654321',
                'role_id' => $organiserRole->id,
                'is_active' => true,
                'commission_rate' => 10.00,
            ]
        );

        // Create sample regular user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'phone' => '5555555555',
                'role_id' => $userRole->id,
                'is_active' => true,
            ]
        );
    }
}
