<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // List all menus
    public function index()
    {
        $menus = Menu::paginate(10);
        return view('admin.menus.index', ['menus' => $menus]);
    }

    // Show create form
    public function create()
    {
        return view('admin.menus.form', ['menu' => null]);
    }

    // Store menu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:menus,name',
            'category' => 'required|in:heavy,beverage,snack',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        Menu::create($validated);

        return redirect('/admin/menus')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Show edit form
    public function edit(Menu $menu)
    {
        return view('admin.menus.form', ['menu' => $menu]);
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:menus,name,' . $menu->id,
            'category' => 'required|in:heavy,beverage,snack',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $menu->update($validated);

        return redirect('/admin/menus')->with('success', 'Menu berhasil diperbarui!');
    }

    // Delete menu
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect('/admin/menus')->with('success', 'Menu berhasil dihapus!');
    }
}
