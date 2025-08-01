<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $appetizer = MenuCategory::firstOrCreate(
            ['name' => 'Appetizer'],
            ['description' => 'Delicious starters to begin your meal']
        );

        $mainCourse = MenuCategory::firstOrCreate(
            ['name' => 'Main Course'],
            ['description' => 'Hearty main dishes']
        );

        $beverages = MenuCategory::firstOrCreate(
            ['name' => 'Beverages'],
            ['description' => 'Refreshing drinks']
        );

        $desserts = MenuCategory::firstOrCreate(
            ['name' => 'Desserts'],
            ['description' => 'Sweet treats to end your meal']
        );

        // Create menu items
        MenuItem::firstOrCreate(
            ['name' => 'Caesar Salad'],
            [
                'category_id' => $appetizer->id,
                'description' => 'Fresh romaine lettuce with caesar dressing, croutons, and parmesan cheese',
                'price' => 45000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Chicken Wings'],
            [
                'category_id' => $appetizer->id,
                'description' => 'Crispy chicken wings with BBQ sauce',
                'price' => 55000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Grilled Salmon'],
            [
                'category_id' => $mainCourse->id,
                'description' => 'Fresh salmon grilled to perfection with herbs and lemon',
                'price' => 125000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Beef Steak'],
            [
                'category_id' => $mainCourse->id,
                'description' => 'Premium beef steak cooked to your preference',
                'price' => 150000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Chicken Teriyaki'],
            [
                'category_id' => $mainCourse->id,
                'description' => 'Tender chicken with teriyaki sauce and steamed rice',
                'price' => 85000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Fresh Orange Juice'],
            [
                'category_id' => $beverages->id,
                'description' => 'Freshly squeezed orange juice',
                'price' => 25000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Iced Coffee'],
            [
                'category_id' => $beverages->id,
                'description' => 'Cold brew coffee with ice',
                'price' => 30000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Chocolate Cake'],
            [
                'category_id' => $desserts->id,
                'description' => 'Rich chocolate cake with chocolate frosting',
                'price' => 40000,
                'is_available' => true
            ]
        );

        MenuItem::firstOrCreate(
            ['name' => 'Ice Cream'],
            [
                'category_id' => $desserts->id,
                'description' => 'Vanilla ice cream with chocolate sauce',
                'price' => 35000,
                'is_available' => true
            ]
        );
    }
}
