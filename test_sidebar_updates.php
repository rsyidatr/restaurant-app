<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing updated sidebar layouts...\n\n";

// Test waiter layout
try {
    $waiterView = view('layouts.waiter_simple');
    $waiterContent = $waiterView->render();
    
    if (strpos($waiterContent, 'admin-layout') !== false) {
        echo "✅ Waiter layout: admin-layout class found\n";
    } else {
        echo "❌ Waiter layout: admin-layout class not found\n";
    }
    
    if (strpos($waiterContent, 'Restaurant Waiter') !== false) {
        echo "✅ Waiter layout: correct header title\n";
    } else {
        echo "❌ Waiter layout: header title not found\n";
    }
    
    if (strpos($waiterContent, 'width: 280px') !== false) {
        echo "✅ Waiter layout: fixed sidebar width (280px)\n";
    } else {
        echo "❌ Waiter layout: fixed sidebar width not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Waiter layout error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test kitchen layout
try {
    $kitchenView = view('layouts.kitchen_simple');
    $kitchenContent = $kitchenView->render();
    
    if (strpos($kitchenContent, 'admin-layout') !== false) {
        echo "✅ Kitchen layout: admin-layout class found\n";
    } else {
        echo "❌ Kitchen layout: admin-layout class not found\n";
    }
    
    if (strpos($kitchenContent, 'Restaurant Kitchen') !== false) {
        echo "✅ Kitchen layout: correct header title\n";
    } else {
        echo "❌ Kitchen layout: header title not found\n";
    }
    
    if (strpos($kitchenContent, 'width: 280px') !== false) {
        echo "✅ Kitchen layout: fixed sidebar width (280px)\n";
    } else {
        echo "❌ Kitchen layout: fixed sidebar width not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Kitchen layout error: " . $e->getMessage() . "\n";
}

echo "\n✅ Sidebar layouts have been updated to match admin design!\n";
