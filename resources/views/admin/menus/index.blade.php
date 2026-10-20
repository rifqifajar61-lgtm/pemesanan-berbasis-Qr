@extends('admin.layouts.app')

@section('title', 'Kelola Menu')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Menu</h1>
        <p class="text-xs text-gray-500">Daftar semua makanan dan minuman yang tersedia</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow">
        + Tambah Menu Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4">Nama Menu</th>
                <th class="p-4">Kategori</th>
                <th class="p-4">Harga</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($menus as $menu)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-900">{{ $menu->name }}</td>
                    <td class="p-4">
                        <span class="bg-stone-100 text-stone-800 text-[10px] font-bold px-2.5 py-1 rounded-full">
                            {{ $menu->category->name ?? 'Tanpa Kategori' }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold text-green-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="p-4">
                        @if($menu->is_available)
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded-full">Tersedia</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-1 rounded-full">Habis</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada menu yang ditambahkan. Silakan klik "+ Tambah Menu Baru".
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection