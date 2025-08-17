<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Login sebagai pelayan untuk test
$waiter = User::where('email', 'pelayan@restaurant.com')->first();

if ($waiter) {
    // Simulate login session
    session_start();
    $_SESSION['user_id'] = $waiter->id;
    $_SESSION['user_role'] = $waiter->role;
    
    echo "Simulated login for: {$waiter->name} ({$waiter->email})\n";
    echo "Role: {$waiter->role}\n";
    echo "User ID: {$waiter->id}\n";
    
    // Test dashboard data
    echo "\nTesting dashboard data...\n";
    
    $ordersCount = \App\Models\Order::count();
    $tablesCount = \App\Models\Table::count();
    
    echo "Orders in database: {$ordersCount}\n";
    echo "Tables in database: {$tablesCount}\n";
    
} else {
    echo "User not found!\n";
}
