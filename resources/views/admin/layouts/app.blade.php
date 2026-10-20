<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Mie Gacoan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    <!-- Sidebar Admin -->
    <aside class="w-64 bg-stone-900 text-white flex-col justify-between hidden md:flex">
        <div>
            <div class="p-5 border-b border-stone-800">
                <h1 class="text-xl font-black text-yellow-400">GACOAN ADMIN</h1>
            </div>

            <nav class="p-4 space-y-1 text-sm font-semibold">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.menus.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.menus.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    🍜 Kelola Menu
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.categories.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    🏷️ Kelola Kategori
                </a>
                <a href="{{ route('admin.employees.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.employees.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    👥 Kelola Pegawai
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.orders.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    🧾 Kelola Pesanan
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.reports.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    📈 Laporan Penjualan
                </a>
                <a href="{{ route('admin.promos.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.promos.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    🎁 Kelola Promo
                </a>
                <a href="{{ route('admin.tables.index') }}"
                   class="block px-4 py-2.5 rounded-lg hover:bg-stone-800 {{ request()->routeIs('admin.tables.*') ? 'bg-red-600 text-white' : 'text-stone-300' }}">
                    🪑 Meja & QR Code
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-stone-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-2 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Panel Kontrol Administrator</h2>
            <span class="text-xs font-semibold bg-red-100 text-red-800 px-3 py-1 rounded-full">
                {{ auth()->user()->name }} (Admin)
            </span>
        </header>

        <main class="p-6 flex-grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>