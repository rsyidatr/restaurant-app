<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@restaurant.com'],
            [
                'name' => 'Restaurant Admin',
                'email' => 'admin@restaurant.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create additional test users
        User::updateOrCreate(
            ['email' => 'pelayan@restaurant.com'],
            [
                'name' => 'Test Pelayan',
                'email' => 'pelayan@restaurant.com',
                'password' => Hash::make('pelayan123'),
                'role' => 'pelayan',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'koki@restaurant.com'],
            [
                'name' => 'Test Koki',
                'email' => 'koki@restaurant.com',
                'password' => Hash::make('koki123'),
                'role' => 'koki',
                'email_verified_at' => now(),
            ]
        );
    }
}
