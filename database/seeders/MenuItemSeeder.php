<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $appetizer = MenuCategory::where('name', 'Appetizer')->first();
        $mainCourse = MenuCategory::where('name', 'Main Course')->first();
        $beverages = MenuCategory::where('name', 'Beverages')->first();
        $desserts = MenuCategory::where('name', 'Desserts')->first();

        $menuItems = [
            // Appetizers
            [
                'category_id' => $appetizer?->id,
                'name' => 'Caesar Salad',
                'description' => 'Segar dengan dressing Caesar dan crouton',
                'price' => 25000,
                'is_available' => true,
                'image_url' => 'images/caesar-salad.jpg'
            ],
            [
                'category_id' => $appetizer?->id,
                'name' => 'Chicken Wings',
                'description' => 'Sayap ayam dengan saus BBQ pedas',
                'price' => 30000,
                'is_available' => true,
                'image_url' => 'images/chicken-wings.jpg'
            ],
            [
                'category_id' => $appetizer?->id,
                'name' => 'Bruschetta',
                'description' => 'Roti panggang dengan tomat dan basil',
                'price' => 20000,
                'is_available' => true,
                'image_url' => 'images/bruschetta.jpg'
            ],

            // Main Courses
            [
                'category_id' => $mainCourse?->id,
                'name' => 'Grilled Salmon',
                'description' => 'Salmon bakar dengan saus lemon herb',
                'price' => 85000,
                'is_available' => true,
                'image_url' => 'images/grilled-salmon.jpg'
            ],
            [
                'category_id' => $mainCourse?->id,
                'name' => 'Beef Steak',
                'description' => 'Daging sapi premium dengan kentang tumbuk',
                'price' => 120000,
                'is_available' => true,
                'image_url' => 'images/beef-steak.jpg'
            ],
            [
                'category_id' => $mainCourse?->id,
                'name' => 'Chicken Parmesan',
                'description' => 'Ayam goreng dengan keju parmesan dan pasta',
                'price' => 65000,
                'is_available' => true,
                'image_url' => 'images/chicken-parmesan.jpg'
            ],
            [
                'category_id' => $mainCourse?->id,
                'name' => 'Seafood Pasta',
                'description' => 'Pasta dengan campuran seafood segar',
                'price' => 75000,
                'is_available' => true,
                'image_url' => 'images/seafood-pasta.jpg'
            ],
            [
                'category_id' => $mainCourse?->id,
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur dan ayam',
                'price' => 35000,
                'is_available' => true,
                'image_url' => 'images/nasi-goreng.jpg'
            ],

            // Beverages
            [
                'category_id' => $beverages?->id,
                'name' => 'Fresh Orange Juice',
                'description' => 'Jus jeruk segar tanpa gula tambahan',
                'price' => 15000,
                'is_available' => true,
                'image_url' => 'images/orange-juice.jpg'
            ],
            [
                'category_id' => $beverages?->id,
                'name' => 'Cappuccino',
                'description' => 'Kopi cappuccino dengan foam art',
                'price' => 18000,
                'is_available' => true,
                'image_url' => 'images/cappuccino.jpg'
            ],
            [
                'category_id' => $beverages?->id,
                'name' => 'Iced Tea',
                'description' => 'Teh es dengan lemon dan mint',
                'price' => 12000,
                'is_available' => true,
                'image_url' => 'images/iced-tea.jpg'
            ],
            [
                'category_id' => $beverages?->id,
                'name' => 'Mineral Water',
                'description' => 'Air mineral kemasan 600ml',
                'price' => 8000,
                'is_available' => true,
                'image_url' => 'images/mineral-water.jpg'
            ],

            // Desserts
            [
                'category_id' => $desserts?->id,
                'name' => 'Chocolate Cake',
                'description' => 'Kue coklat lembut dengan frosting coklat',
                'price' => 30000,
                'is_available' => true,
                'image_url' => 'images/chocolate-cake.jpg'
            ],
            [
                'category_id' => $desserts?->id,
                'name' => 'Tiramisu',
                'description' => 'Dessert Italia dengan kopi dan mascarpone',
                'price' => 35000,
                'is_available' => true,
                'image_url' => 'images/tiramisu.jpg'
            ],
            [
                'category_id' => $desserts?->id,
                'name' => 'Ice Cream Sundae',
                'description' => 'Es krim vanilla dengan topping buah',
                'price' => 25000,
                'is_available' => true,
                'image_url' => 'images/ice-cream-sundae.jpg'
            ],
            [
                'category_id' => $desserts?->id,
                'name' => 'Cheesecake',
                'description' => 'Kue keju dengan berry sauce',
                'price' => 32000,
                'is_available' => true,
                'image_url' => 'images/cheesecake.jpg'
            ],
        ];

        foreach ($menuItems as $item) {
            if ($item['category_id']) {
                MenuItem::create($item);
            }
        }
    }
}
