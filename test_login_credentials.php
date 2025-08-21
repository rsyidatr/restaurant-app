<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Testing login credentials for all roles:\n\n";

$testUsers = [
    'admin@restaurant.com' => ['password' => 'password', 'role' => 'admin'],
    'pelayan@restaurant.com' => ['password' => 'password', 'role' => 'pelayan'],
    'koki@restaurant.com' => ['password' => 'password', 'role' => 'koki'],
    'customer@test.com' => ['password' => 'password', 'role' => 'pelanggan'],
];

foreach ($testUsers as $email => $data) {
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        echo "❌ {$email} - User not found\n";
        continue;
    }
    
    // Test password
    if (Hash::check($data['password'], $user->password)) {
        echo "✅ {$email} - Password: ✓ - Role: {$user->role}";
        
        // Check role
        if ($user->role === $data['role']) {
            echo " - Role: ✓\n";
        } else {
            echo " - Role: ❌ (Expected: {$data['role']}, Got: {$user->role})\n";
        }
    } else {
        echo "❌ {$email} - Password: ❌\n";
    }
}

echo "\nAll users should now be able to login with password: 'password'\n";
