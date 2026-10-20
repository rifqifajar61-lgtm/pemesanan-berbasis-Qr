<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuManagementController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(Menu $menu)
    {
        return redirect()->route('admin.menus.edit', $menu);
    }

    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->orderItems()->exists()) {
            return back()->with('error', 'Menu ini sudah pernah dipesan, tidak bisa dihapus. Ubah statusnya menjadi "Habis" saja.');
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }
}