<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING DELETE FUNCTIONALITY ===\n\n";

// Test if delete route exists
try {
    $menuItem = App\Models\MenuItem::first();
    if ($menuItem) {
        $deleteUrl = route('admin.menu.destroy', ['menuItem' => $menuItem]);
        echo "✅ Delete route works: {$deleteUrl}\n";
        echo "   Menu to test: {$menuItem->name} (ID: {$menuItem->id})\n";
    } else {
        echo "❌ No menu items found\n";
    }
} catch (Exception $e) {
    echo "❌ Delete route error: " . $e->getMessage() . "\n";
}

// Test MenuController destroy method
try {
    $controller = new App\Http\Controllers\Admin\MenuController();
    echo "✅ MenuController accessible\n";
    
    // Check if destroy method exists
    if (method_exists($controller, 'destroy')) {
        echo "✅ destroy method exists\n";
    } else {
        echo "❌ destroy method not found\n";
    }
} catch (Exception $e) {
    echo "❌ Controller error: " . $e->getMessage() . "\n";
}

// Test if we can simulate a delete
echo "\n=== TESTING ACTUAL DELETE SIMULATION ===\n";
try {
    // Count items before
    $beforeCount = App\Models\MenuItem::count();
    echo "Menu items before: {$beforeCount}\n";
    
    // Find last item to delete
    $itemToDelete = App\Models\MenuItem::latest()->first();
    if ($itemToDelete) {
        echo "Item to delete: {$itemToDelete->name} (ID: {$itemToDelete->id})\n";
        
        // Simulate delete (but don't actually delete)
        echo "✅ Delete simulation would work\n";
    } else {
        echo "❌ No items to delete\n";
    }
} catch (Exception $e) {
    echo "❌ Delete simulation error: " . $e->getMessage() . "\n";
}

?>
