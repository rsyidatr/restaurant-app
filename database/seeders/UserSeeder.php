<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin dummy
        User::create([
            'name' => 'Admin Restaurant',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Pelanggan dummy
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer'
        ]);

        // Pelayan dummy
        User::create([
            'name' => 'Pelayan Restaurant',
            'email' => 'pelayan@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'pelayan'
        ]);

        // Koki dummy
        User::create([
            'name' => 'Kitchen Test',
            'email' => 'kitchen@test.com',
            'password' => Hash::make('password123'),
            'role' => 'koki'
        ]);

        // Test users dengan password yang sama untuk kemudahan testing
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Waiter Test',
            'email' => 'waiter@test.com',
            'password' => Hash::make('password'),
            'role' => 'pelayan'
        ]);

        User::create([
            'name' => 'Chef Test',
            'email' => 'chef@test.com',
            'password' => Hash::make('password'),
            'role' => 'koki'
        ]);
    }
}
