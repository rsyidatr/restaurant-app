<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING MENU ROUTES AND FUNCTIONALITY ===\n\n";

// Test if MenuItem model exists and has data
try {
    $menuItems = App\Models\MenuItem::with('category')->get();
    echo "✅ Found " . $menuItems->count() . " menu items:\n";
    
    foreach ($menuItems->take(3) as $item) {
        echo "   - ID: {$item->id}, Name: {$item->name}, Category: " . ($item->category ? $item->category->name : 'None') . "\n";
        
        // Test route generation for each item
        try {
            $showRoute = route('admin.menu.show', $item);
            echo "     Show URL: {$showRoute}\n";
        } catch (Exception $e) {
            echo "     ❌ Show route error: " . $e->getMessage() . "\n";
        }
        
        try {
            $editRoute = route('admin.menu.edit', $item);
            echo "     Edit URL: {$editRoute}\n";
        } catch (Exception $e) {
            echo "     ❌ Edit route error: " . $e->getMessage() . "\n";
        }
        
        try {
            $destroyRoute = route('admin.menu.destroy', $item);
            echo "     Delete URL: {$destroyRoute}\n";
        } catch (Exception $e) {
            echo "     ❌ Delete route error: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error accessing menu items: " . $e->getMessage() . "\n";
}

// Test categories
try {
    $categories = App\Models\MenuCategory::all();
    echo "✅ Found " . $categories->count() . " categories:\n";
    foreach ($categories as $cat) {
        echo "   - ID: {$cat->id}, Name: {$cat->name}\n";
    }
} catch (Exception $e) {
    echo "❌ Error accessing categories: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING ROUTE PARAMETER BINDING ===\n";

// Test route model binding
try {
    $firstItem = App\Models\MenuItem::first();
    if ($firstItem) {
        echo "Testing with menu item ID: {$firstItem->id}\n";
        
        // Simulate route model binding
        $boundItem = App\Models\MenuItem::findOrFail($firstItem->id);
        echo "✅ Route model binding works for ID {$firstItem->id}\n";
        echo "   Bound item: {$boundItem->name}\n";
    }
} catch (Exception $e) {
    echo "❌ Route model binding error: " . $e->getMessage() . "\n";
}

?>
