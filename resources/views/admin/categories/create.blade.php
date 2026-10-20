@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 mb-1">Nama Kategori</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="contoh: Mie Pedas"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('slug') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-[11px] text-gray-400 mt-1">Slug dibuat otomatis dari nama kategori.</p>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Simpan</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection