<?php

require_once 'vendor/autoload.php';

// Load Laravel app  
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING ACTUAL DELETE OPERATION ===\n\n";

try {
    // Create a test menu item first
    $testItem = App\Models\MenuItem::create([
        'name' => 'Test Delete Item',
        'description' => 'This is a test item for deletion',
        'price' => 10000,
        'category_id' => 1, // Assuming category 1 exists
        'is_available' => true
    ]);
    
    echo "✅ Created test item: {$testItem->name} (ID: {$testItem->id})\n";
    
    // Count before deletion
    $beforeCount = App\Models\MenuItem::count();
    echo "Menu items before deletion: {$beforeCount}\n";
    
    // Try to delete using the controller method
    $controller = new App\Http\Controllers\Admin\MenuController();
    
    // Simulate the destroy method
    $menuName = $testItem->name;
    $testItem->delete();
    
    // Count after deletion
    $afterCount = App\Models\MenuItem::count();
    echo "Menu items after deletion: {$afterCount}\n";
    
    if ($afterCount == $beforeCount - 1) {
        echo "✅ Delete operation successful!\n";
        echo "✅ Item '{$menuName}' was deleted successfully\n";
    } else {
        echo "❌ Delete operation failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error during delete test: " . $e->getMessage() . "\n";
}

echo "\n=== CHECKING AUTHENTICATION AND PERMISSIONS ===\n";

// Check if admin user exists and can access
try {
    $adminUser = App\Models\User::where('email', 'admin@resto.com')->first();
    if ($adminUser) {
        echo "✅ Admin user exists: {$adminUser->name} (Role: {$adminUser->role})\n";
        
        if ($adminUser->role === 'admin') {
            echo "✅ Admin has correct role permissions\n";
        } else {
            echo "❌ Admin does not have admin role\n";
        }
    } else {
        echo "❌ Admin user not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking admin user: " . $e->getMessage() . "\n";
}

?>
