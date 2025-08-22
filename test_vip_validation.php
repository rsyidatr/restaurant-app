<?php

// Simple test to verify table number validation works for VIP tables
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Validator;

// Test data
$testCases = [
    ['table_number' => 'VIP01', 'capacity' => 30, 'status' => 'available'],
    ['table_number' => 'VIP02', 'capacity' => 30, 'status' => 'reserved'], 
    ['table_number' => '25', 'capacity' => 4, 'status' => 'available'],
    ['table_number' => 'A01', 'capacity' => 6, 'status' => 'available'],
    ['table_number' => '123456789012345', 'capacity' => 2, 'status' => 'available'], // Should fail - too long
];

// Validation rules
$rules = [
    'table_number' => 'required|string|max:10',
    'capacity' => 'required|integer|in:2,4,6,8,10,15,20,30',
    'status' => 'required|in:available,reserved,occupied,cleaning'
];

echo "Testing table number validation:\n\n";

foreach ($testCases as $index => $data) {
    echo "Test case " . ($index + 1) . ": " . $data['table_number'] . "\n";
    
    $validator = Validator::make($data, $rules);
    
    if ($validator->fails()) {
        echo "❌ FAILED: " . implode(', ', $validator->errors()->all()) . "\n";
    } else {
        echo "✅ PASSED: Validation successful\n";
    }
    echo "\n";
}
