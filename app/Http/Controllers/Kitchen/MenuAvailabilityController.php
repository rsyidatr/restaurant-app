<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::with('category');
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter berdasarkan availability
        if ($request->filled('availability')) {
            $isAvailable = $request->availability === 'available';
            $query->where('is_available', $isAvailable);
        }
        
        // Tampilkan semua menu items
        $menuItems = $query->orderBy('name')->paginate(15);
        $categories = MenuCategory::orderBy('name')->get();
        
        // Statistik untuk dashboard
        $totalCount = MenuItem::count();
        $availableCount = MenuItem::where('is_available', true)->count();
        $unavailableCount = MenuItem::where('is_available', false)->count();
        $categoriesCount = MenuCategory::count();
        
        return view('kitchen.menu.index', compact(
            'menuItems', 
            'categories', 
            'totalCount', 
            'availableCount', 
            'unavailableCount', 
            'categoriesCount'
        ));
    }
    
    public function toggleAvailability(Request $request, MenuItem $menuItem)
    {
        // Gunakan nilai is_available dari request jika ada, jika tidak lakukan toggle biasa
        if ($request->has('is_available')) {
            $menuItem->is_available = $request->boolean('is_available');
        } else {
            $menuItem->is_available = !$menuItem->is_available;
        }
        
        $menuItem->save();
        
        $status = $menuItem->is_available ? 'tersedia' : 'tidak tersedia';
        
        return response()->json([
            'success' => true,
            'message' => "Menu '{$menuItem->name}' berhasil diubah menjadi {$status}",
            'is_available' => $menuItem->is_available
        ]);
    }
    
    public function bulkUpdateAvailability(Request $request)
    {
        $request->validate([
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:menu_items,id',
            'availability' => 'required|boolean'
        ]);
        
        MenuItem::whereIn('id', $request->menu_ids)
                ->update(['is_available' => $request->availability]);
        
        $status = $request->availability ? 'tersedia' : 'tidak tersedia';
        $count = count($request->menu_ids);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} item menu berhasil diubah menjadi {$status}"
        ]);
    }
    
    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category');
        
        return view('kitchen.menu.show', compact('menuItem'));
    }
}
