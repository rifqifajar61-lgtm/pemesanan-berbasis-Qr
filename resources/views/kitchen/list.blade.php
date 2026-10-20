@extends('kitchen.layouts.app')

@section('title', $config['title'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $config['title'] }}</h2>
        <p class="text-xs text-gray-500">{{ $config['subtitle'] }} &middot; {{ $orders->count() }} pesanan</p>
    </div>
    <p class="text-[11px] text-gray-400 hidden md:block">Diperbarui otomatis tiap 30 detik</p>
</div>

@if($orders->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400 text-sm font-semibold">
        {{ $config['empty'] }}
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($orders as $order)
            @php
                $minutes = (int) $order->created_at->diffInMinutes(now());
                $late = $minutes >= 15;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border-2 {{ $late ? 'border-red-400' : 'border-gray-200' }} flex flex-col">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex justify-between items-start gap-2">
                        <p class="text-sm font-black text-gray-900">{{ $order->order_code }}</p>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap {{ $late ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700' }}">
                            {{ $minutes }} menit
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $order->order_type === 'takeaway' ? 'bg-purple-100 text-purple-800' : 'bg-stone-100 text-stone-800' }}">
                            {{ $order->order_type === 'takeaway' ? 'Bawa Pulang' : 'Makan di Tempat' }}
                        </span>
                        @if($order->order_type !== 'takeaway')
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Meja {{ $order->table_number ?? '-' }}</span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $order->customer_name }}</span>
                    </div>
                </div>

                <ul class="p-4 space-y-2 flex-1 text-sm">
                    @foreach($order->items as $item)
                        <li>
                            <div class="flex gap-2">
                                <span class="font-black text-red-600">{{ $item->quantity }}x</span>
                                <span class="font-semibold text-gray-800">{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                            </div>
                            @if(!empty($item->notes))
                                <p class="text-xs text-orange-600 font-semibold ml-6">📝 {{ $item->notes }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('kitchen.update-status', $order->id) }}" method="POST" class="p-4 pt-0">
                    @csrf
                    <input type="hidden" name="action" value="{{ $config['action'] }}">
                    <button type="submit" class="w-full {{ $config['color'] }} text-white text-sm font-bold py-2.5 rounded-lg">
                        {{ $config['button'] }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>setTimeout(() => location.reload(), 30000);</script>
@endpush