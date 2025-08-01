<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->orderBy('created_at', 'desc')->paginate(10);
        $categories = MenuCategory::all();
        
        return view('admin.menu.index', compact('menuItems', 'categories'));
    }
    
    public function create()
    {
        $categories = MenuCategory::all();
        return view('admin.menu.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('menu', 'public');
        }
        
        MenuItem::create($validated);
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu item created successfully.');
    }
    
    public function show(MenuItem $menuItem)
    {
        return view('admin.menu.show', compact('menuItem'));
    }
    
    public function edit(MenuItem $menuItem)
    {
        $categories = MenuCategory::all();
        return view('admin.menu.edit', compact('menuItem', 'categories'));
    }
    
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menuItem->image_url) {
                Storage::disk('public')->delete($menuItem->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('menu', 'public');
        }
        
        $menuItem->update($validated);
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully.');
    }
    
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image_url) {
            Storage::disk('public')->delete($menuItem->image_url);
        }
        
        $menuItem->delete();
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted successfully.');
    }
    
    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_available' => !$menuItem->is_available
        ]);
        
        $status = $menuItem->is_available ? 'available' : 'unavailable';
        
        return response()->json([
            'success' => true,
            'message' => "Menu item is now {$status}",
            'is_available' => $menuItem->is_available
        ]);
    }
    
    // Category management
}