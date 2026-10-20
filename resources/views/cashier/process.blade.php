@extends('cashier.layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pembayaran</h2>
            <p class="text-xs text-gray-500">Kode: <span class="font-bold text-gray-700">{{ $order->order_code }}</span></p>
        </div>
        <a href="{{ route('cashier.scan') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">&larr; Scan Lagi</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Rincian pesanan --}}
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500">
                <span>Meja: <b class="text-gray-800">{{ $order->table->table_number ?? '-' }}</b></span>
                <span>Pelanggan: <b class="text-gray-800">{{ $order->customer_name ?? '-' }}</b></span>
                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                        <th class="p-3">Menu</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="p-3">
                                <span class="font-bold text-gray-900">{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                                @if(!empty($item->notes))
                                    <p class="text-xs text-gray-400">Catatan: {{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="2" class="p-3 text-right text-xs font-bold text-gray-600 uppercase">Total</td>
                        <td class="p-3 text-right text-lg font-black text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Panel pembayaran --}}
        <div class="lg:col-span-2 space-y-4">
            @if($order->payment_status === 'paid')
                <div class="bg-green-50 border border-green-300 rounded-xl p-5 text-center">
                    <p class="text-2xl mb-1">✅</p>
                    <p class="text-sm font-bold text-green-800">Pesanan sudah dibayar</p>
                    <p class="text-xs text-green-700 mt-1">{{ strtoupper($order->payment_method) }} &middot; {{ $order->paid_at?->format('d M Y H:i') }}</p>
                    <a href="{{ route('cashier.success', $order->id) }}" class="inline-block mt-3 text-xs font-bold text-green-800 underline">Lihat struk</a>
                </div>
            @else
                {{-- Tunai --}}
                <form action="{{ route('cashier.payCash', $order->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-3">
                    @csrf
                    <h3 class="text-sm font-bold text-gray-800">💵 Bayar Tunai</h3>

                    <div>
                        <label for="cash_received" class="block text-xs font-bold text-gray-600 mb-1">Uang diterima (Rp)</label>
                        <input type="number" id="cash_received" name="cash_received" min="{{ $order->total_amount }}" step="500" required
                               value="{{ old('cash_received') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('cash_received') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap gap-1">
                        <button type="button" class="quick-cash bg-gray-100 hover:bg-gray-200 text-xs font-bold px-3 py-1.5 rounded-lg" data-value="{{ $order->total_amount }}">Uang pas</button>
                        @foreach([50000, 100000] as $nominal)
                            @if($nominal >= $order->total_amount)
                                <button type="button" class="quick-cash bg-gray-100 hover:bg-gray-200 text-xs font-bold px-3 py-1.5 rounded-lg" data-value="{{ $nominal }}">{{ number_format($nominal, 0, ',', '.') }}</button>
                            @endif
                        @endforeach
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 flex justify-between items-center">
                        <span class="text-xs font-semibold text-gray-500">Kembalian</span>
                        <span id="change" class="text-lg font-black text-gray-800">Rp 0</span>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2.5 rounded-lg">Konfirmasi Tunai</button>
                </form>

                {{-- QRIS --}}
                <form action="{{ route('cashier.payQris', $order->id) }}" method="POST"
                      onsubmit="return confirm('Pastikan pembayaran QRIS sebesar Rp {{ number_format($order->total_amount, 0, ',', '.') }} sudah masuk. Lanjutkan?')"
                      class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-3">
                    @csrf
                    <h3 class="text-sm font-bold text-gray-800">📱 Bayar QRIS</h3>
                    <p class="text-xs text-gray-500">Minta pelanggan memindai QRIS toko, cek notifikasi pembayaran masuk, lalu konfirmasi.</p>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-lg">Konfirmasi QRIS Diterima</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const total = {{ (int) $order->total_amount }};
    const cashInput = document.getElementById('cash_received');
    const changeEl = document.getElementById('change');

    function updateChange() {
        if (!cashInput) return;
        const change = (parseInt(cashInput.value) || 0) - total;
        changeEl.textContent = 'Rp ' + Math.max(change, 0).toLocaleString('id-ID');
        changeEl.className = 'text-lg font-black ' + (change < 0 ? 'text-red-600' : 'text-gray-800');
    }

    if (cashInput) {
        cashInput.addEventListener('input', updateChange);
        document.querySelectorAll('.quick-cash').forEach(btn => {
            btn.addEventListener('click', () => {
                cashInput.value = btn.dataset.value;
                updateChange();
            });
        });
        updateChange();
    }
</script>
@endpush