@extends('cashier.layouts.app')

@section('title', 'Transaksi Berhasil')

@section('content')
<div class="max-w-sm mx-auto">
    <div class="text-center mb-4 print:hidden">
        <p class="text-4xl mb-1">✅</p>
        <h2 class="text-2xl font-bold text-green-700">Transaksi Berhasil</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-sm">
        <div class="text-center border-b border-dashed border-gray-300 pb-3 mb-3">
            <p class="font-black text-lg text-gray-900">MIE GACOAN</p>
            <p class="text-xs text-gray-500">Struk Pembayaran</p>
        </div>

        <div class="text-xs text-gray-600 space-y-1 mb-3">
            <div class="flex justify-between"><span>Kode</span><b class="text-gray-900">{{ $order->order_code }}</b></div>
            <div class="flex justify-between"><span>Pelanggan</span><span>{{ $order->customer_name }}</span></div>
            <div class="flex justify-between">
                <span>Jenis</span>
                <span>{{ $order->order_type === 'takeaway' ? 'Bawa Pulang' : 'Meja ' . ($order->table_number ?? '-') }}</span>
            </div>
            <div class="flex justify-between"><span>Waktu</span><span>{{ $order->created_at->format('d M Y H:i') }}</span></div>
            <div class="flex justify-between"><span>Kasir</span><span>{{ auth()->user()->name }}</span></div>
        </div>

        <div class="border-t border-dashed border-gray-300 pt-3 space-y-2">
            @foreach($order->items as $item)
                <div class="flex justify-between gap-2 text-xs">
                    <span>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu dihapus' }}</span>
                    <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-gray-300 mt-3 pt-3 space-y-1 text-xs">
            <div class="flex justify-between text-sm font-black text-gray-900">
                <span>TOTAL</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Metode</span><span>{{ $order->payment_method === 'cash' ? 'TUNAI' : strtoupper($order->payment_method) }}</span>
            </div>
            @if($order->payment_method === 'cash' && $order->cash_amount)
                <div class="flex justify-between text-gray-600">
                    <span>Tunai</span><span>Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900">
                    <span>Kembalian</span><span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">Terima kasih atas kunjungan Anda!</p>
    </div>

    <div class="flex flex-wrap gap-2 mt-4 print:hidden">
        <button onclick="window.print()" class="flex-1 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold py-2.5 rounded-lg">🖨️ Cetak Struk</button>
        <a href="{{ route('cashier.scan') }}" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2.5 rounded-lg">Pesanan Berikutnya</a>
        <a href="{{ route('cashier.dashboard') }}" class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold py-2.5 rounded-lg">Ke Dashboard</a>
    </div>
</div>
@endsection