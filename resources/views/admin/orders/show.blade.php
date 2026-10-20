@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
            <p class="text-xs text-gray-500">Kode: <span class="font-bold text-gray-700">{{ $order->order_code }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">
            &larr; Kembali
        </a>
    </div>

    {{-- Informasi pesanan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <p class="text-xs text-gray-500 font-semibold">Pelanggan</p>
            <p class="text-sm font-bold text-gray-800 mt-1">{{ $order->customer_name ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold">Meja</p>
            <p class="text-sm font-bold text-gray-800 mt-1">{{ $order->table->table_number ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold">Pembayaran</p>
            <p class="mt-1">
                @if($order->payment_status === 'paid')
                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Lunas</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Belum Dibayar</span>
                @endif
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold">Metode</p>
            <p class="text-sm font-bold text-gray-800 mt-1">{{ strtoupper($order->payment_method ?? '-') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold">Status Dapur</p>
            <p class="text-sm font-bold text-gray-800 mt-1">{{ ucfirst($order->order_status ?? '-') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold">Waktu Pesan</p>
            <p class="text-sm font-bold text-gray-800 mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    {{-- Daftar item --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                    <th class="p-4">Menu</th>
                    <th class="p-4 text-center">Qty</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                @foreach($order->items as $item)
                    <tr>
                        <td class="p-4">
                            <span class="font-bold text-gray-900">{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                            @if(!empty($item->notes))
                                <p class="text-xs text-gray-400 mt-0.5">Catatan: {{ $item->notes }}</p>
                            @endif
                        </td>
                        <td class="p-4 text-center">{{ $item->quantity }}</td>
                        <td class="p-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-right font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="3" class="p-4 text-right text-xs font-bold text-gray-600 uppercase">Total</td>
                    <td class="p-4 text-right text-lg font-black text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection