@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Kategori</h1>
        <p class="text-xs text-gray-500">Daftar kategori untuk mengelompokkan menu</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow">
        + Tambah Kategori
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4 w-12">No</th>
                <th class="p-4">Nama Kategori</th>
                <th class="p-4">Slug</th>
                <th class="p-4 text-center">Jumlah Menu</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $category->name }}</td>
                    <td class="p-4 text-xs text-gray-500">{{ $category->slug }}</td>
                    <td class="p-4 text-center">
                        <span class="bg-stone-100 text-stone-800 text-[10px] font-bold px-2.5 py-1 rounded-full">
                            {{ $category->menus_count }} menu
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada kategori. Silakan klik "+ Tambah Kategori".
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection