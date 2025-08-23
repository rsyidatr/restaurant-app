<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua kategori menu beserta item-itemnya yang tersedia
        $menuCategories = MenuCategory::with(['menuItems' => function($query) {
            $query->where('is_available', true)->orderBy('name');
        }])
        ->whereHas('menuItems', function($query) {
            $query->where('is_available', true);
        })
        ->orderBy('name')
        ->get();

        // Format data untuk kompatibilitas dengan view yang sudah ada
        $formattedCategories = [];
        foreach($menuCategories as $category) {
            $items = [];
            foreach($category->menuItems as $item) {
                // Handle image path
                $imagePath = 'default-menu.jpg';
                if ($item->image_url) {
                    if (str_contains($item->image_url, 'menu/')) {
                        // New storage path
                        $imagePath = $item->image_url;
                    } else {
                        // Old public/images/menu path
                        $imagePath = 'images/menu/' . $item->image_url;
                    }
                }
                
                $items[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'image' => $imagePath
                ];
            }
            
            if (!empty($items)) {
                $formattedCategories[strtolower(str_replace(' ', '-', $category->name))] = [
                    'name' => $category->name,
                    'items' => $items
                ];
            }
        }

        return view('customer.menu.index', ['menuCategories' => $formattedCategories]);
    }
}
