<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get some test data
        $customer = User::where('role', 'customer')->first();
        $menuItems = MenuItem::take(3)->get();
        $table = Table::first();

        if ($customer && $menuItems->count() > 0 && $table) {
            // Create sample order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-001',
                'user_id' => $customer->id,
                'table_id' => $table->id,
                'order_type' => 'dine_in',
                'status' => 'pending',
                'total_amount' => 150000,
                'grand_total' => 150000,
                'order_date' => now(),
                'customer_name' => $customer->name,
                'customer_phone' => '081234567890',
                'notes' => 'Test order for waiter dashboard'
            ]);

            // Add order items
            foreach ($menuItems as $index => $menuItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'menu_name' => $menuItem->name,
                    'quantity' => $index + 1,
                    'price' => $menuItem->price
                ]);
            }

            // Create another order with different status
            $order2 = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-002',
                'user_id' => $customer->id,
                'table_id' => $table->id,
                'order_type' => 'dine_in',
                'status' => 'preparing',
                'total_amount' => 85000,
                'grand_total' => 85000,
                'order_date' => now(),
                'customer_name' => $customer->name,
                'customer_phone' => '081234567890',
                'notes' => 'Another test order'
            ]);

            if ($menuItems->count() > 1) {
                OrderItem::create([
                    'order_id' => $order2->id,
                    'menu_item_id' => $menuItems[1]->id,
                    'menu_name' => $menuItems[1]->name,
                    'quantity' => 2,
                    'price' => $menuItems[1]->price
                ]);
            }
        }
    }
}
