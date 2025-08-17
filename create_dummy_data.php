<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\User;

// Buat beberapa pesanan dummy untuk testing
$table1 = Table::first();
$menuItem = MenuItem::first();
$waiter = User::where('role', 'pelayan')->first();

if ($table1 && $menuItem && $waiter) {
    for ($i = 1; $i <= 5; $i++) {
        $order = Order::create([
            'order_number' => 'ORD' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
            'table_id' => $table1->id,
            'waiter_id' => $waiter->id,
            'customer_name' => 'Customer ' . $i,
            'customer_phone' => '08123456789' . $i,
            'order_type' => 'dine_in',
            'status' => ['pending', 'preparing', 'ready', 'served'][array_rand(['pending', 'preparing', 'ready', 'served'])],
            'payment_status' => 'paid',
            'total_amount' => 50000 + ($i * 10000),
            'subtotal' => 45000 + ($i * 10000),
            'tax_amount' => 5000,
            'service_charge' => 0,
            'grand_total' => 50000 + ($i * 10000),
            'payment_method' => 'cash',
            'order_date' => now(),
            'payment_date' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $menuItem->id,
            'menu_name' => $menuItem->name,
            'quantity' => rand(1, 3),
            'price' => (int)$menuItem->price,
            'notes' => 'Catatan khusus untuk item ' . $i
        ]);
    }

    echo "Data dummy berhasil dibuat!\n";
} else {
    echo "Data dasar tidak ditemukan!\n";
}
