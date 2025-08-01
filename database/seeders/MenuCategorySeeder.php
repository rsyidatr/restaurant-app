<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizer',
                'description' => 'Hidangan pembuka yang lezat'
            ],
            [
                'name' => 'Main Course',
                'description' => 'Hidangan utama yang mengenyangkan'
            ],
            [
                'name' => 'Dessert',
                'description' => 'Hidangan penutup yang manis'
            ],
            [
                'name' => 'Beverages',
                'description' => 'Minuman segar dan hangat'
            ],
            [
                'name' => 'Snacks',
                'description' => 'Camilan ringan'
            ]
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category);
        }
    }
}
