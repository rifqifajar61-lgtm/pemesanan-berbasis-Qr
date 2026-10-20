@extends('kitchen.layouts.app')

@section('title', 'Dashboard Dapur')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Dapur</h2>
    <p class="text-xs text-gray-500">{{ now()->translatedFormat('l, d F Y') }} &middot; halaman diperbarui otomatis tiap 30 detik</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('kitchen.orders') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-orange-400">
        <p class="text-xs text-gray-500 font-semibold">Antrean Baru</p>
        <p class="text-3xl font-black text-orange-600 mt-1">{{ $countNew }}</p>
    </a>
    <a href="{{ route('kitchen.processing') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-400">
        <p class="text-xs text-gray-500 font-semibold">Sedang Dimasak</p>
        <p class="text-3xl font-black text-red-600 mt-1">{{ $countProcessing }}</p>
    </a>
    <a href="{{ route('kitchen.ready') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-blue-400">
        <p class="text-xs text-gray-500 font-semibold">Siap Disajikan</p>
        <p class="text-3xl font-black text-blue-600 mt-1">{{ $countReady }}</p>
    </a>
    <a href="{{ route('kitchen.history') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-green-400">
        <p class="text-xs text-gray-500 font-semibold">Selesai Hari Ini</p>
        <p class="text-3xl font-black text-green-600 mt-1">{{ $countDoneToday }}</p>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-sm font-bold text-gray-800">Antrean Terlama</h3>
        <a href="{{ route('kitchen.orders') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat semua</a>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($newOrders as $order)
            <div class="p-4 flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-gray-900">
                        {{ $order->order_code }}
                        <span class="ml-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $order->order_type === 'takeaway' ? 'bg-purple-100 text-purple-800' : 'bg-stone-100 text-stone-800' }}">
                            {{ $order->order_type === 'takeaway' ? 'Bawa Pulang' : 'Meja ' . ($order->table_number ?? '-') }}
                        </span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $order->items->map(fn ($i) => $i->quantity . 'x ' . ($i->menu->name ?? 'Menu dihapus'))->implode(', ') }}
                    </p>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $order->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <p class="p-6 text-center text-gray-400 text-xs font-semibold">Tidak ada antrean. Dapur sedang santai 👌</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>setTimeout(() => location.reload(), 30000);</script>
@endpush