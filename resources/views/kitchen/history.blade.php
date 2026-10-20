@extends('kitchen.layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-3 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h2>
        <p class="text-xs text-gray-500">
            Pesanan selesai &middot; {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </p>
    </div>

    <form action="{{ route('kitchen.history') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-xs w-32 focus:outline-none focus:ring-2 focus:ring-red-500">
        <input type="date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-xs">
        <span class="text-xs text-gray-400">s/d</span>
        <input type="date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-xs">
        <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold px-4 py-2 rounded-lg">Filter</button>
        <a href="{{ route('kitchen.history') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Reset</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4">Kode</th>
                <th class="p-4">Jenis</th>
                <th class="p-4">Pesanan</th>
                <th class="p-4">Dipesan</th>
                <th class="p-4">Selesai</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="p-4 font-bold text-gray-900">{{ $order->order_code }}</td>
                    <td class="p-4 text-xs">
                        {{ $order->order_type === 'takeaway' ? 'Bawa Pulang' : 'Meja ' . ($order->table_number ?? '-') }}
                    </td>
                    <td class="p-4 text-xs">
                        @foreach($order->items as $item)
                            <div>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu dihapus' }}</div>
                        @endforeach
                    </td>
                    <td class="p-4 text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="p-4 text-xs text-gray-500">{{ $order->updated_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400 text-xs font-semibold">Tidak ada pesanan selesai pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection