<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pelanggan dummy
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan'
        ]);

        // Pelayan dummy
        User::create([
            'name' => 'Waiter Test',
            'email' => 'waiter@test.com',
            'password' => Hash::make('password123'),
            'role' => 'pelayan'
        ]);

        // Koki dummy
        User::create([
            'name' => 'Kitchen Test',
            'email' => 'kitchen@test.com',
            'password' => Hash::make('password123'),
            'role' => 'koki'
        ]);
    }
}
