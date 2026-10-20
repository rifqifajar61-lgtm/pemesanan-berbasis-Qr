@extends('admin.layouts.app')

@section('title', 'Kelola Promo')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Promo</h1>
        <p class="text-xs text-gray-500">Daftar kode promo dan diskon</p>
    </div>
    <a href="{{ route('admin.promos.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow">
        + Tambah Promo
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
                <th class="p-4">Nama Promo</th>
                <th class="p-4">Kode</th>
                <th class="p-4 text-center">Diskon</th>
                <th class="p-4">Periode</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($promos as $promo)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-900">{{ $promo->name }}</td>
                    <td class="p-4">
                        <span class="bg-stone-100 text-stone-800 text-xs font-mono font-bold px-2.5 py-1 rounded">
                            {{ $promo->code }}
                        </span>
                    </td>
                    <td class="p-4 text-center font-semibold text-green-600">{{ $promo->discount_percent }}%</td>
                    <td class="p-4 text-xs text-gray-500">
                        {{ $promo->start_date->format('d M Y') }} &ndash; {{ $promo->end_date->format('d M Y') }}
                    </td>
                    <td class="p-4">
                        @php
                            $today = now()->startOfDay();
                            if (!$promo->is_active) {
                                $label = 'Nonaktif';
                                $style = 'bg-gray-100 text-gray-800';
                            } elseif ($today->lt($promo->start_date)) {
                                $label = 'Belum Mulai';
                                $style = 'bg-blue-100 text-blue-800';
                            } elseif ($today->gt($promo->end_date)) {
                                $label = 'Kedaluwarsa';
                                $style = 'bg-red-100 text-red-800';
                            } else {
                                $label = 'Berlaku';
                                $style = 'bg-green-100 text-green-800';
                            }
                        @endphp
                        <span class="{{ $style }} text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $label }}</span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.promos.edit', $promo->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                        <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada promo. Silakan klik "+ Tambah Promo".
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection