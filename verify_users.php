<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Verifying users in database:\n\n";

$emails = ['admin@restaurant.com', 'pelayan@restaurant.com', 'koki@restaurant.com', 'customer@test.com'];

foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "✅ {$user->email} - {$user->name} - Role: {$user->role}\n";
    } else {
        echo "❌ {$email} - NOT FOUND\n";
    }
}

echo "\nTotal users: " . User::count() . "\n";
