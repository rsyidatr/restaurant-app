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
        
        // Filter berdasarkan status ketersediaan
        if ($request->filled('availability')) {
            $availability = $request->availability === 'available' ? true : false;
            $query->where('is_available', $availability);
        }
        
        $menuItems = $query->orderBy('name')->paginate(15);
        $categories = MenuCategory::orderBy('name')->get();
        
        return view('kitchen.menu.index', compact('menuItems', 'categories'));
    }
    
    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->is_available = !$menuItem->is_available;
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
