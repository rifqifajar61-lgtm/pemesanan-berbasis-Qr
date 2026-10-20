@extends('admin.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kategori</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 mb-1">Nama Kategori</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('slug') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Slug saat ini</label>
            <input type="text" value="{{ $category->slug }}" disabled
                   class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500">
            <p class="text-[11px] text-gray-400 mt-1">Slug akan diperbarui otomatis mengikuti nama baru.</p>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Perbarui</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection