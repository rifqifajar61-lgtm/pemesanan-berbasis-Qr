@extends('cashier.layouts.app')

@section('title', 'Scan QR Pesanan')

@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Scan QR Pesanan</h2>
    <p class="text-xs text-gray-500 mb-4">Arahkan kamera ke QR Code pada layar pelanggan</p>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
        <div id="reader" class="w-full overflow-hidden rounded-lg"></div>
        <p id="scan-status" class="text-center text-xs text-gray-400 mt-3">Memulai kamera...</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs font-bold text-gray-600 mb-2">Atau masukkan kode pesanan manual</p>
        <form id="lookup-form" action="{{ route('cashier.lookup') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" id="code" name="code" required placeholder="contoh: ORD-20261020-001"
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Cari</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    const status = document.getElementById('scan-status');
    const form = document.getElementById('lookup-form');
    const input = document.getElementById('code');
    let handled = false;

    function onScanSuccess(text) {
        if (handled) return;
        handled = true;
        status.textContent = 'QR terbaca, membuka pesanan...';
        input.value = text;
        scanner.stop().catch(() => {}).finally(() => form.submit());
    }

    const scanner = new Html5Qrcode('reader');
    scanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 240, height: 240 } },
        onScanSuccess
    ).then(() => {
        status.textContent = 'Kamera aktif. Arahkan ke QR Code.';
    }).catch(() => {
        status.textContent = 'Kamera tidak bisa dibuka (butuh izin kamera dan HTTPS/localhost). Gunakan input manual.';
        status.classList.replace('text-gray-400', 'text-red-500');
    });
</script>
@endpush