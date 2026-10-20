@extends('cashier.layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Kasir</h2>
    <p class="text-xs text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Pendapatan Hari Ini</p>
        <p class="text-xl font-black text-green-600 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Transaksi Hari Ini</p>
        <p class="text-xl font-black text-blue-600 mt-1">{{ $todayTransactions }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Menunggu Pembayaran</p>
        <p class="text-xl font-black text-orange-600 mt-1">{{ $unpaidCount }}</p>
    </div>
</div>

<a href="{{ route('cashier.scan') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow mb-6">
    📷 Scan QR Pesanan
</a>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Belum dibayar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Pesanan Belum Dibayar</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($unpaidOrders as $order)
                <div class="p-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $order->order_code }}</p>
                        <p class="text-xs text-gray-500">
                            Meja {{ $order->table->table_number ?? '-' }} &middot; {{ $order->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <a href="{{ route('cashier.process', $order->order_code) }}" class="text-xs font-bold text-red-600 hover:underline">Proses &rarr;</a>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-gray-400 text-xs font-semibold">Tidak ada pesanan yang menunggu pembayaran.</p>
            @endforelse
        </div>
    </div>

    {{-- Transaksi terakhir --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-800">Transaksi Terakhir</h3>
            <a href="{{ route('cashier.history') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentPaid as $order)
                <div class="p-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $order->order_code }}</p>
                        <p class="text-xs text-gray-500">
                            {{ strtoupper($order->payment_method) }} &middot; {{ $order->paid_at?->format('H:i') }}
                        </p>
                    </div>
                    <p class="text-sm font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="p-6 text-center text-gray-400 text-xs font-semibold">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection