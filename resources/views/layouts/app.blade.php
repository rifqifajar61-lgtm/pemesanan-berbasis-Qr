<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Gacoan - Pesan Sendiri</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        logo: ['"Bebas Neue"', 'Impact', 'sans-serif'],
                        hand: ['Caveat', 'cursive'],
                    },
                    colors: {
                        ink: '#0f0b09',
                        brand: '#ffc62b',
                        brandDark: '#f2b300',
                        gpink: '#ec1c8f',
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .swoosh { background: linear-gradient(transparent 82%, #ffc62b 82%, #ffc62b 92%, transparent 92%); }
    </style>
</head>
<body class="bg-[#fffdf8] text-gray-800 min-h-screen flex flex-col font-sans antialiased">

    <!-- Navbar -->
    <header class="bg-ink text-white sticky top-0 z-40 border-b border-white/10">
        <div class="max-w-[90rem] mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
                <span class="w-9 h-9 rounded-full bg-gpink flex items-center justify-center font-logo text-lg leading-none rotate-[-8deg]">ME</span>
                <span class="font-logo text-3xl tracking-wide leading-none">GACOAN</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-white/80">
                <a href="{{ url('/') }}#beranda" class="hover:text-brand">Beranda</a>
                <a href="{{ url('/') }}#menu" class="hover:text-brand">Menu</a>
                <a href="{{ url('/') }}#promo" class="hover:text-brand">Promo</a>
                <a href="{{ url('/') }}#cara-pesan" class="hover:text-brand">Cara Pesan</a>
            </nav>

            <div class="flex items-center gap-2">
                @hasSection('cart-enabled')
                    <button type="button" onclick="openCart()" class="relative w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/10" aria-label="Buka keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H5.4M7 13L5.4 5M7 13l-2.3 2.3a1 1 0 00.7 1.7H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span id="cart-count" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-gpink text-[10px] font-bold flex items-center justify-center">0</span>
                    </button>
                @endif

                @auth
                    <span class="hidden sm:inline text-xs bg-white/10 px-3 py-1.5 rounded-full font-semibold">{{ strtoupper(auth()->user()->role) }}</span>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-xs bg-brand hover:bg-brandDark text-ink px-4 py-2 rounded-lg font-bold">Dashboard Admin</a>
                    @elseif(auth()->user()->role === 'cashier')
                        <a href="{{ route('cashier.scan') }}" class="text-xs bg-brand hover:bg-brandDark text-ink px-4 py-2 rounded-lg font-bold">Scan QR Kasir</a>
                    @elseif(auth()->user()->role === 'kitchen')
                        <a href="{{ route('kitchen.index') }}" class="text-xs bg-brand hover:bg-brandDark text-ink px-4 py-2 rounded-lg font-bold">Antrean Dapur</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs border border-white/30 hover:bg-white/10 px-4 py-2 rounded-lg font-semibold">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs bg-brand hover:bg-brandDark text-ink font-bold px-4 py-2 rounded-lg">Login Pegawai</a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success') || session('error'))
        <div class="max-w-[90rem] mx-auto px-4 pt-4 w-full">
            @if(session('success'))
                <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
<footer class="bg-ink text-white/70 text-xs">
    <div class="max-w-[90rem] mx-auto px-4 py-6 text-center">
        <p>&copy; {{ date('Y') }} Mie Gacoan - Sistem Pesan Mandiri</p>
    </div>
</footer>

</body>
</html>