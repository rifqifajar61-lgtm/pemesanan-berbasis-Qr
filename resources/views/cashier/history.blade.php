@extends('cashier.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-3 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
        <p class="text-xs text-gray-500">
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </p>
    </div>

    <form action="{{ route('cashier.history') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-xs w-32 focus:outline-none focus:ring-2 focus:ring-red-500">
        <select name="method" class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">Semua metode</option>
            <option value="cash" @selected(request('method') === 'cash')>Tunai</option>
            <option value="qris" @selected(request('method') === 'qris')>QRIS</option>
        </select>
        <input type="date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-xs">
        <span class="text-xs text-gray-400">s/d</span>
        <input type="date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-xs">
        <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold px-4 py-2 rounded-lg">Filter</button>
        <a href="{{ route('cashier.history') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Reset</a>
    </form>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">{{ $errors->first() }}</div>
@endif

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Transaksi</p>
        <p class="text-xl font-black text-blue-600 mt-1">{{ number_format($totalCount, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Pendapatan</p>
        <p class="text-xl font-black text-green-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4">Kode</th>
                <th class="p-4">Waktu</th>
                <th class="p-4">Jenis</th>
                <th class="p-4">Metode</th>
                <th class="p-4 text-right">Total</th>
                <th class="p-4 text-center">Struk</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-900">{{ $order->order_code }}</td>
                    <td class="p-4 text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="p-4 text-xs">
                        {{ $order->order_type === 'takeaway' ? 'Bawa Pulang' : 'Meja ' . ($order->table_number ?? '-') }}
                    </td>
                    <td class="p-4">
                        <span class="{{ $order->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} text-[10px] font-bold px-2.5 py-1 rounded-full">
                            {{ $order->payment_method === 'cash' ? 'Tunai' : 'QRIS' }}
                        </span>
                    </td>
                    <td class="p-4 text-right font-semibold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('cashier.success', $order->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-400 text-xs font-semibold">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection