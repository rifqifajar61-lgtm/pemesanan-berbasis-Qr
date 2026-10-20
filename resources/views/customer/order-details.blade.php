@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-red-600">
    <h2 class="text-2xl font-bold text-red-600 mb-2">Pesanan Berhasil!</h2>
    <p class="text-gray-600 text-sm mb-4">Tunjukkan QR Code ini ke Kasir untuk Pembayaran</p>

    <div class="flex justify-center my-4">
        <div id="qrcode"></div>
    </div>

    <p class="font-mono text-lg font-bold text-gray-800 mb-1">{{ $order->order_code }}</p>
    <p class="text-xs text-gray-500 mb-4">Nama: {{ $order->customer_name }} | Tipe: {{ strtoupper($order->order_type) }}</p>

    <div class="border-t pt-4 text-left">
        <h3 class="font-bold text-sm mb-2">Rincian Menu:</h3>
        @foreach($order->items as $item)
            <div class="flex justify-between text-xs py-1">
                <span>{{ $item->menu->name }} x{{ $item->quantity }}</span>
                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
        <div class="flex justify-between font-bold border-t mt-2 pt-2 text-sm">
            <span>Total:</span>
            <span class="text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $order->order_code }}",
        width: 180,
        height: 180,
        colorDark: "#dc2626",
        colorLight: "#ffffff",
    });
</script>
@endsection