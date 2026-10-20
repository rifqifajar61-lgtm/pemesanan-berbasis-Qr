@extends('admin.layouts.app')

@section('title', 'Edit Promo')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Promo</h1>

    <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 mb-1">Nama Promo</label>
            <input type="text" id="name" name="name" value="{{ old('name', $promo->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="code" class="block text-xs font-bold text-gray-600 mb-1">Kode Promo</label>
            <input type="text" id="code" name="code" value="{{ old('code', $promo->code) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="discount_percent" class="block text-xs font-bold text-gray-600 mb-1">Diskon (%)</label>
            <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent', $promo->discount_percent) }}" min="1" max="100" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('discount_percent') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-xs font-bold text-gray-600 mb-1">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $promo->start_date->format('Y-m-d')) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('start_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="end_date" class="block text-xs font-bold text-gray-600 mb-1">Tanggal Berakhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $promo->end_date->format('Y-m-d')) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('end_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo->is_active))
                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
            Promo aktif
        </label>

        <div class="flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Perbarui</button>
            <a href="{{ route('admin.promos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection