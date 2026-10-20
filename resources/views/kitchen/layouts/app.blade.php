<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dapur') - Mie Gacoan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    @php
        $counts = [
            'paid'    => \App\Models\Order::where('status', 'paid')->count(),
            'kitchen' => \App\Models\Order::where('status', 'kitchen')->count(),
            'ready'   => \App\Models\Order::where('status', 'ready')->count(),
        ];

        $links = [
            ['route' => 'kitchen.dashboard',  'label' => '📊 Dashboard',         'badge' => null],
            ['route' => 'kitchen.orders',     'label' => '📋 Daftar Pesanan',    'badge' => $counts['paid']],
            ['route' => 'kitchen.processing', 'label' => '🔥 Proses Pesanan',    'badge' => $counts['kitchen']],
            ['route' => 'kitchen.ready',      'label' => '🍽️ Siap Disajikan',   'badge' => $counts['ready']],
            ['route' => 'kitchen.history',    'label' => '🧾 Riwayat Pesanan',   'badge' => null],
        ];
    @endphp

    <!-- Sidebar Dapur -->
    <aside class="w-64 bg-stone-900 text-white flex-col justify-between hidden md:flex">
        <div>
            <div class="p-5 border-b border-stone-800">
                <h1 class="text-xl font-black text-yellow-400">GACOAN DAPUR</h1>
            </div>

            <nav class="p-4 space-y-1 text-sm font-semibold">
                @foreach($links as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs($item['route']) ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                        <span>{{ $item['label'] }}</span>
                        @if($item['badge'])
                            <span class="bg-yellow-400 text-stone-900 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
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
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-2 rounded-lg">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Area Konten -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center gap-3">
            <nav class="flex md:hidden gap-1 text-xs font-bold overflow-x-auto">
                @foreach($links as $item)
                    <a href="{{ route($item['route']) }}"
                       class="whitespace-nowrap px-2.5 py-1.5 rounded-lg {{ request()->routeIs($item['route']) ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <h2 class="hidden md:block text-lg font-bold text-gray-800">Panel Dapur</h2>
            <span class="text-xs font-semibold bg-red-100 text-red-800 px-3 py-1 rounded-full whitespace-nowrap">
                {{ auth()->user()->name }} ({{ auth()->user()->role === 'admin' ? 'Admin' : 'Dapur' }})
            </span>
        </header>

        <main class="p-4 md:p-6 flex-grow">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>