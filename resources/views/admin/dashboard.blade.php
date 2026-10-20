@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Pendapatan Hari Ini</p>
        <p class="text-xl font-black text-green-600 mt-1">Rp {{ number_format($todayEarnings ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Pesanan Masuk</p>
        <p class="text-xl font-black text-blue-600 mt-1">{{ $totalOrders ?? 0 }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Menu Aktif</p>
        <p class="text-xl font-black text-orange-600 mt-1">{{ $totalMenus ?? 0 }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Pegawai</p>
        <p class="text-xl font-black text-purple-600 mt-1">{{ $totalEmployees ?? 0 }}</p>
    </div>
</div>
@endsection