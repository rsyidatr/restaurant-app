<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing kitchen dashboard view...\n";
    
    // Test data untuk dashboard koki
    $data = [
        'todayOrders' => 12,
        'pendingOrders' => 3,
        'processingOrders' => 2,
        'readyOrders' => 1,
        'recentOrders' => collect([])
    ];
    
    // Test view rendering
    $view = view('kitchen.dashboard', $data);
    $content = $view->render();
    
    echo "✅ Kitchen dashboard view rendered successfully!\n";
    echo "Content length: " . strlen($content) . " characters\n";
    
    // Check for common issues
    if (strpos($content, 'Cannot end a section') !== false) {
        echo "❌ Found 'Cannot end a section' error in output\n";
    } else {
        echo "✅ No section errors found\n";
    }
    
    if (strpos($content, 'cdn.tailwindcss.com') !== false) {
        echo "✅ Tailwind CSS CDN loaded\n";
    } else {
        echo "❌ Tailwind CSS CDN not found\n";
    }
    
    if (strpos($content, 'font-awesome') !== false) {
        echo "✅ Font Awesome loaded\n";
    } else {
        echo "❌ Font Awesome not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
