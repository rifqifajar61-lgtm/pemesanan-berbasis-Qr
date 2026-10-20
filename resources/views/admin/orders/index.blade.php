@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h1>
        <p class="text-xs text-gray-500">Pantau semua pesanan yang masuk</p>
    </div>

    {{-- Filter status pembayaran --}}
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-2">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">Semua status</option>
            <option value="unpaid" @selected(request('status') === 'unpaid')>Belum Dibayar</option>
            <option value="paid" @selected(request('status') === 'paid')>Sudah Dibayar</option>
        </select>
        <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold px-4 py-2 rounded-lg">Filter</button>
        @if(request()->filled('status'))
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Reset</a>
        @endif
    </form>
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
                <th class="p-4">Kode</th>
                <th class="p-4">Pelanggan</th>
                <th class="p-4">Meja</th>
                <th class="p-4">Total</th>
                <th class="p-4">Pembayaran</th>
                <th class="p-4">Status Dapur</th>
                <th class="p-4">Waktu</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-900">{{ $order->order_code }}</td>
                    <td class="p-4">{{ $order->customer_name ?? '-' }}</td>
                    <td class="p-4">{{ $order->table->table_number ?? '-' }}</td>
                    <td class="p-4 font-semibold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4">
                        @if($order->payment_status === 'paid')
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Lunas</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @php
                            $statusStyle = match ($order->order_status) {
                                'pending' => 'bg-gray-100 text-gray-800',
                                'cooking' => 'bg-orange-100 text-orange-800',
                                'done'    => 'bg-blue-100 text-blue-800',
                                default   => 'bg-gray-100 text-gray-800',
                            };
                            $statusLabel = match ($order->order_status) {
                                'pending' => 'Menunggu',
                                'cooking' => 'Dimasak',
                                'done'    => 'Selesai',
                                default   => ucfirst($order->order_status ?? '-'),
                            };
                        @endphp
                        <span class="{{ $statusStyle }} text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $statusLabel }}</span>
                    </td>
                    <td class="p-4 text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada pesanan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection