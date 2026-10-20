@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h1>
        <p class="text-xs text-gray-500">
            Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
            &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} (hanya pesanan lunas)
        </p>
    </div>

    {{-- Filter tanggal --}}
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}"
               class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-500">
        <span class="text-xs text-gray-400">s/d</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
               class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-500">
        <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold px-4 py-2 rounded-lg">Tampilkan</button>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Reset</a>
        <button type="button" onclick="window.print()" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Cetak</button>
    </form>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ $errors->first() }}
    </div>
@endif

{{-- Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Pendapatan</p>
        <p class="text-xl font-black text-green-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Jumlah Pesanan</p>
        <p class="text-xl font-black text-blue-600 mt-1">{{ number_format($totalOrders, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Rata-rata per Pesanan</p>
        <p class="text-xl font-black text-orange-600 mt-1">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-semibold">Total Item Terjual</p>
        <p class="text-xl font-black text-purple-600 mt-1">{{ number_format($totalItemsSold, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Penjualan per hari --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Penjualan per Hari</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3 text-center">Pesanan</th>
                        <th class="p-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                    @forelse($dailySales as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ \Carbon\Carbon::parse($row->sale_date)->format('d M Y') }}</td>
                            <td class="p-3 text-center">{{ $row->total_orders }}</td>
                            <td class="p-3 text-right font-semibold text-green-600">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-400 text-xs font-semibold">
                                Tidak ada penjualan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($dailySales->isNotEmpty())
                    <tfoot>
                        <tr class="bg-gray-50 font-bold text-sm">
                            <td class="p-3">Total</td>
                            <td class="p-3 text-center">{{ $totalOrders }}</td>
                            <td class="p-3 text-right text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Menu terlaris --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">10 Menu Terlaris</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                        <th class="p-3 w-10">#</th>
                        <th class="p-3">Menu</th>
                        <th class="p-3 text-center">Terjual</th>
                        <th class="p-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                    @forelse($topMenus as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                            <td class="p-3 font-bold text-gray-900">{{ $item->menu->name ?? 'Menu dihapus' }}</td>
                            <td class="p-3 text-center">{{ $item->total_qty }}</td>
                            <td class="p-3 text-right font-semibold text-green-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400 text-xs font-semibold">
                                Belum ada menu terjual pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection