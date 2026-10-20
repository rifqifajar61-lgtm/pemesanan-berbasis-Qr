@extends('admin.layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Menu</h1>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 mb-1">Nama Menu</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="block text-xs font-bold text-gray-600 mb-1">Kategori</label>
            <select id="category_id" name="category_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">-- Pilih kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @if($categories->isEmpty())
                <p class="text-[11px] text-red-500 mt-1">
                    Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="underline font-bold">Buat kategori dulu</a>.
                </p>
            @endif
        </div>

        <div>
            <label for="price" class="block text-xs font-bold text-gray-600 mb-1">Harga (Rp)</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="500" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-gray-600 mb-1">Deskripsi <span class="font-normal text-gray-400">(opsional)</span></label>
            <textarea id="description" name="description" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="image" class="block text-xs font-bold text-gray-600 mb-1">Foto Menu <span class="font-normal text-gray-400">(opsional, maks 2 MB)</span></label>
            <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp"
                   class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-stone-900 file:text-white file:text-xs file:font-bold">
            @error('image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', true))
                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
            Menu tersedia
        </label>

        <div class="flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Simpan</button>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection