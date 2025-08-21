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
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create pelayan user
        User::updateOrCreate(
            ['email' => 'pelayan@restaurant.com'],
            [
                'name' => 'Pelayan Restaurant',
                'email' => 'pelayan@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'pelayan',
                'email_verified_at' => now(),
            ]
        );

        // Create koki user
        User::updateOrCreate(
            ['email' => 'koki@restaurant.com'],
            [
                'name' => 'Koki Restaurant',
                'email' => 'koki@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'koki',
                'email_verified_at' => now(),
            ]
        );

        // Create pelanggan user
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Test Customer',
                'email' => 'customer@test.com',
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
                'email_verified_at' => now(),
            ]
        );
    }
}
