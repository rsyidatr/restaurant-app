<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "Testing direct login simulation:\n\n";

// Test admin login
$admin = User::where('email', 'admin@restaurant.com')->first();
if ($admin && Hash::check('password', $admin->password)) {
    echo "✅ Admin credentials valid\n";
    echo "   - Email: {$admin->email}\n";
    echo "   - Role: {$admin->role}\n";
    echo "   - Should redirect to: admin.dashboard\n\n";
} else {
    echo "❌ Admin credentials invalid\n\n";
}

// Test customer login
$customer = User::where('email', 'customer@test.com')->first();
if ($customer && Hash::check('password', $customer->password)) {
    echo "✅ Customer credentials valid\n";
    echo "   - Email: {$customer->email}\n";
    echo "   - Role: {$customer->role}\n";
    echo "   - Should redirect to: customer.home\n\n";
} else {
    echo "❌ Customer credentials invalid\n\n";
}

// Check if routes exist
try {
    $adminRoute = route('admin.dashboard');
    echo "✅ admin.dashboard route exists: {$adminRoute}\n";
} catch (Exception $e) {
    echo "❌ admin.dashboard route missing: " . $e->getMessage() . "\n";
}

try {
    $customerRoute = route('customer.home');
    echo "✅ customer.home route exists: {$customerRoute}\n";
} catch (Exception $e) {
    echo "❌ customer.home route missing: " . $e->getMessage() . "\n";
}
