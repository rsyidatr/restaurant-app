<?php

use Illuminate\Support\Facades\Route;
use App\Models\MenuItem;
use App\Models\MenuCategory;

// Debug route to test menu functionality without authentication
Route::get('/debug-menu', function () {
    $menuItems = MenuItem::with('category')->paginate(10);
    $categories = MenuCategory::orderBy('name')->get();
    
    return view('admin.menu.index', compact('menuItems', 'categories'));
});

// Debug route to test specific menu item
Route::get('/debug-menu/{id}', function ($id) {
    $menuItem = MenuItem::findOrFail($id);
    return view('admin.menu.show', compact('menuItem'));
});

// Debug route to test edit
Route::get('/debug-menu/{id}/edit', function ($id) {
    $menuItem = MenuItem::findOrFail($id);
    $categories = MenuCategory::orderBy('name')->get();
    return view('admin.menu.edit', compact('menuItem', 'categories'));
});

// Debug route to test delete
Route::post('/debug-menu/{id}/delete', function ($id) {
    $menuItem = MenuItem::findOrFail($id);
    $menuName = $menuItem->name;
    $menuItem->delete();
    
    return redirect('/debug-menu')->with('success', "Menu '{$menuName}' berhasil dihapus.");
});

// Debug route to test toggle availability
Route::post('/debug-menu/{id}/toggle-availability', function ($id) {
    $menuItem = MenuItem::findOrFail($id);
    $menuItem->update([
        'is_available' => !$menuItem->is_available
    ]);
    
    $status = $menuItem->is_available ? 'tersedia' : 'tidak tersedia';
    
    return response()->json([
        'success' => true,
        'message' => "Menu '{$menuItem->name}' sekarang {$status}",
        'is_available' => $menuItem->is_available
    ]);
});

?>
