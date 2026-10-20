<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryManagementController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->menus()->exists()) {
            return back()->with('error', 'Kategori masih dipakai oleh menu, tidak bisa dihapus.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('name', ''))]);

        return $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'slug')->ignore($category?->id),
            ],
        ], [
            'name.unique' => 'Nama kategori sudah dipakai.',
            'slug.unique' => 'Nama ini menghasilkan slug yang sudah ada, gunakan nama lain.',
            'slug.required' => 'Nama kategori harus mengandung huruf atau angka.',
        ]);
    }
}