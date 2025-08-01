<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::withCount('menuItems')->paginate(10);
        return view('admin.menu-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.menu-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories',
            'description' => 'nullable|string'
        ]);

        MenuCategory::create($request->all());

        return redirect()->route('admin.menu-categories.index')
                        ->with('success', 'Kategori menu berhasil ditambahkan.');
    }

    public function show(MenuCategory $menuCategory)
    {
        $menuCategory->load('menuItems');
        return view('admin.menu-categories.show', compact('menuCategory'));
    }

    public function edit(MenuCategory $menuCategory)
    {
        return view('admin.menu-categories.edit', compact('menuCategory'));
    }

    public function update(Request $request, MenuCategory $menuCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories,name,' . $menuCategory->id,
            'description' => 'nullable|string'
        ]);

        $menuCategory->update($request->all());

        return redirect()->route('admin.menu-categories.index')
                        ->with('success', 'Kategori menu berhasil diperbarui.');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        if ($menuCategory->menuItems()->count() > 0) {
            return redirect()->route('admin.menu-categories.index')
                            ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki menu item.');
        }

        $menuCategory->delete();

        return redirect()->route('admin.menu-categories.index')
                        ->with('success', 'Kategori menu berhasil dihapus.');
    }
}
