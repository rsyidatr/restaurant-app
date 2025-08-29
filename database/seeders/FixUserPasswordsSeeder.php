<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixUserPasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Restaurant',
                'email' => 'admin@restaurant.com',
                'role' => 'admin'
            ],
            [
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'role' => 'admin'
            ],
            [
                'name' => 'Pelayan Restaurant',
                'email' => 'pelayan@restaurant.com',
                'role' => 'pelayan'
            ],
            [
                'name' => 'Waiter Test',
                'email' => 'waiter@test.com',
                'role' => 'pelayan'
            ],
            [
                'name' => 'Kitchen Test',
                'email' => 'kitchen@test.com',
                'role' => 'koki'
            ],
            [
                'name' => 'Chef Test',
                'email' => 'chef@test.com',
                'role' => 'koki'
            ],
            [
                'name' => 'Customer Test',
                'email' => 'customer@test.com',
                'role' => 'customer'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::where('email', $userData['email'])->first();
            
            if ($user) {
                // Update existing user
                $user->update([
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => Hash::make('password')
                ]);
                $this->command->info("Updated user: {$userData['email']}");
            } else {
                // Create new user
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'role' => $userData['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now()
                ]);
                $this->command->info("Created user: {$userData['email']}");
            }
        }

        $this->command->info('All user passwords have been reset to: password');
    }
}
