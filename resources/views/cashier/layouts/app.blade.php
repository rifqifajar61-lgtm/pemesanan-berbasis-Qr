<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir') - Mie Gacoan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    @php
        $links = [
            ['route' => 'cashier.dashboard', 'match' => 'cashier.dashboard',  'label' => '📊 Dashboard'],
            ['route' => 'cashier.scan',      'match' => 'cashier.scan',       'label' => '📷 Scan QR Pesanan'],
            ['route' => 'cashier.history',   'match' => 'cashier.history',    'label' => '🧾 Riwayat Transaksi'],
        ];
    @endphp

    <!-- Sidebar Kasir -->
    <aside class="w-64 bg-stone-900 text-white flex-col justify-between hidden md:flex print:hidden">
        <div>
            <div class="p-5 border-b border-stone-800">
                <h1 class="text-xl font-black text-yellow-400">GACOAN KASIR</h1>
            </div>

            <nav class="p-4 space-y-1 text-sm font-semibold">
                @foreach($links as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs($item['match']) ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- Halaman pembayaran & struk tetap menandai menu Scan QR --}}
                @if(request()->routeIs('cashier.process', 'cashier.success'))
                    <div class="px-4 py-2 text-xs text-stone-500">
                        {{ request()->routeIs('cashier.process') ? 'Sedang memproses pembayaran' : 'Transaksi berhasil' }}
                    </div>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-stone-800 space-y-2">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="block w-full text-center bg-stone-800 hover:bg-stone-700 text-yellow-400 text-xs font-bold py-2 rounded-lg">
                    ← Panel Admin
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-2 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Area Konten -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center print:hidden">
            <div class="flex items-center gap-3">
                {{-- Menu ringkas untuk layar kecil --}}
                <nav class="flex md:hidden gap-1 text-xs font-bold">
                    @foreach($links as $item)
                        <a href="{{ route($item['route']) }}"
                           class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs($item['match']) ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <h2 class="hidden md:block text-lg font-bold text-gray-800">Panel Kasir</h2>
            </div>
            <span class="text-xs font-semibold bg-red-100 text-red-800 px-3 py-1 rounded-full">
                {{ auth()->user()->name }} ({{ auth()->user()->role === 'admin' ? 'Admin' : 'Kasir' }})
            </span>
        </header>

        <main class="p-4 md:p-6 flex-grow">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>