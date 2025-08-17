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
                $items[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'image' => $item->image_url ? $item->image_url : 'default-menu.jpg'
                ];
            }
            
            if (!empty($items)) {
                $formattedCategories[strtolower(str_replace(' ', '-', $category->name))] = [
                    'name' => $category->name,
                    'items' => $items
                ];
            }
        }

        return view('customer.menu', ['menuCategories' => $formattedCategories]);
    }
}
