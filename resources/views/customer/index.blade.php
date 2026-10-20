@extends('layouts.app')

@section('cart-enabled', '1')

@section('content')

@php
    // Emoji kategori berdasarkan kata di nama kategori
    $icon = function ($name) {
        $n = strtolower($name);
        return match (true) {
            str_contains($n, 'dimsum') || str_contains($n, 'siomay') => '🥟',
            str_contains($n, 'minum') || str_contains($n, 'es ') || str_contains($n, 'teh') => '🥤',
            str_contains($n, 'paket') => '🧰',
            str_contains($n, 'pedas') || str_contains($n, 'level') => '🌶️',
            str_contains($n, 'snack') || str_contains($n, 'camilan') || str_contains($n, 'goreng') => '🍤',
            str_contains($n, 'nasi') => '🍚',
            str_contains($n, 'mie') || str_contains($n, 'mi ') => '🍜',
            default => '🍽️',
        };
    };
@endphp

<!-- HERO + PENCARIAN -->
<section id="beranda" class="pt-5">
    <div class="max-w-[90rem] mx-auto px-4"> 
        <div class="relative">
            <img src="{{ asset('images/header.png') }}"
                 alt="Mie Gacoan selalu jadi pilihan: mie pedas, dimsum, dan minuman segar"
                 class="w-full h-56 sm:h-auto object-cover object-left rounded-2xl block">

            <!-- Di HP: di bawah foto. Di layar sedang ke atas: menimpa foto -->
            <div class="mt-3 sm:mt-0 sm:absolute sm:left-[4%] sm:top-[71%] sm:w-[40%] sm:min-w-[220px]">
                <div class="flex bg-white rounded-xl overflow-hidden shadow-lg border border-gray-200 sm:border-0 focus-within:ring-2 focus-within:ring-brand">
                    <div class="flex items-center pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                    </div>
                    <input id="search" type="text" placeholder="Cari menu favoritmu..."
                           class="flex-1 min-w-0 px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none">
                    <a href="#menu" class="bg-brand hover:bg-brandDark text-ink px-5 flex items-center text-sm font-extrabold">Cari</a>
                </div>

                <div class="mt-2 flex sm:hidden lg:flex flex-wrap items-center gap-2 text-xs">
                    <span class="font-bold text-gray-800 sm:text-white">Populer:</span>
                    @foreach ($menus->take(5) as $pop)
                        <button type="button" onclick="quickSearch(@js($pop->name))"
                                class="px-3 py-1 rounded-full border border-gray-300 text-gray-700 hover:bg-yellow-50 sm:border-white/40 sm:text-white sm:bg-black/30 sm:hover:bg-black/50">{{ $pop->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section class="max-w-[90rem] mx-auto px-4 pt-8">
    <div class="flex md:justify-between gap-5 overflow-x-auto no-scrollbar pb-2">
       <button type="button" data-cat="all" onclick="filterCategory('all')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-brand flex items-center justify-center text-3xl">🍽️</span>
            <span class="cat-label text-sm font-bold text-brandDark">Semua</span>
        </button>

        <button type="button" data-cat="1" onclick="filterCategory('1')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">🍜</span>
            <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">Mie</span>
        </button>

        <button type="button" data-cat="2" onclick="filterCategory('2')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">🥟</span>
            <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">Dimsum</span>
        </button>

        <button type="button" data-cat="3" onclick="filterCategory('3')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">🥤</span>
            <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">Minuman</span>
        </button>

        <button type="button" data-cat="4" onclick="filterCategory('4')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">🍱</span>
            <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">Paket</span>
        </button>

        <button type="button" data-cat="5" onclick="filterCategory('5')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
            <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">🍟</span>
            <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">Makanan Ringan</span>
        </button>

        @foreach ($categories as $category)
            <button type="button" data-cat="{{ $category->id }}" onclick="filterCategory('{{ $category->id }}')" class="cat-btn flex flex-col items-center gap-2 shrink-0 w-24">
                <span class="cat-circle w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl">{{ $icon($category->name) }}</span>
                <span class="cat-label text-sm font-medium text-gray-700 text-center leading-tight">{{ $category->name }}</span>
            </button>
        @endforeach
    </div>
</section>

<!-- MENU ANDALAN -->
<section id="menu" class="max-w-[90rem] mx-auto px-4 pt-8 pb-10 scroll-mt-20">
    <div class="flex items-end justify-between mb-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold">Menu Andalan</h2>
            <p class="text-sm text-gray-500">Menu favorit yang selalu jadi pilihan pelanggan.</p>
        </div>
        <button type="button" onclick="resetFilter()" class="text-sm font-semibold hover:text-brandDark">Lihat Semua</button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @forelse ($menus as $menu)
            <div class="menu-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col"
                 data-cat="{{ $menu->category_id }}" data-name="{{ strtolower($menu->name) }}">
                @if ($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center text-5xl">🍜</div>
                @endif

                <div class="p-3 flex flex-col flex-1">
                    <h3 class="font-extrabold text-sm text-gray-900 leading-tight">{{ $menu->name }}</h3>
                    <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $menu->description ?? 'Pedas gurih khas Mie Gacoan.' }}</p>

                    <div class="mt-auto pt-3">
                        <p class="font-extrabold text-sm mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <button type="button"
                                onclick="addToCart({{ $menu->id }}, @js($menu->name), {{ $menu->price }})"
                                class="w-full bg-brand hover:bg-brandDark active:scale-95 text-ink text-xs font-extrabold py-2 rounded-lg transition">
                            + Keranjang
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 p-8 rounded-2xl text-center text-gray-500 text-sm">
                Belum ada menu yang tersedia.
            </div>
        @endforelse
    </div>

    <p id="no-result" class="hidden mt-4 text-center text-sm text-gray-500 bg-gray-50 rounded-2xl p-8">
        Menu tidak ditemukan. Coba kata kunci atau kategori lain.
    </p>
</section>

<!-- BANNER PROMO -->
<section id="promo" class="max-w-[90rem] mx-auto px-4 scroll-mt-20">
    <div class="relative overflow-hidden rounded-2xl bg-ink text-white p-6 md:p-10 flex items-center justify-between gap-6">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_85%_50%,rgba(255,198,43,0.3),transparent_60%)]"></div>
        <div class="relative">
            <p class="text-sm font-bold text-brand">Promo Spesial</p>
            <h3 class="text-3xl md:text-5xl font-extrabold leading-tight mt-1">Pedas <span class="text-brand">Sepuasnya</span></h3>
            <p class="mt-2 text-sm text-white/75">Tanyakan promo hari ini ke kasir saat menunjukkan QR Code.</p>
            <a href="#menu" class="inline-block mt-5 bg-brand hover:bg-brandDark text-ink text-sm font-extrabold px-6 py-2.5 rounded-lg">Pesan Sekarang</a>
        </div>
        <div class="relative hidden sm:flex items-center gap-4">
            <span class="text-[8rem] md:text-[9rem] leading-none">🍜</span>
            <span class="hidden md:block font-hand text-3xl text-white rotate-[-6deg] leading-tight">Makan Lebih Hemat,<br>Lebih Nikmat!</span>
        </div>
    </div>
</section>

<!-- KENAPA PILIH GACOAN -->
<section class="max-w-[90rem] mx-auto px-4 py-12">
    <h2 class="text-2xl md:text-3xl font-extrabold">Kenapa Pilih Gacoan?</h2>
    <p class="text-sm text-gray-500 mb-8">Rasa yang enak, harga yang pas, dan selalu ada yang baru.</p>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-start">
        <div>
            <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center text-3xl mb-3">🍜</div>
            <h3 class="font-extrabold text-sm">Rasa Lezat</h3>
            <p class="text-xs text-gray-500 mt-1">Bumbu khas yang bikin nagih.</p>
        </div>
        <div>
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-3xl mb-3">💰</div>
            <h3 class="font-extrabold text-sm">Harga Terjangkau</h3>
            <p class="text-xs text-gray-500 mt-1">Nikmatnya gak bikin kantong bolong.</p>
        </div>
        <div>
            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-3xl mb-3">🥟</div>
            <h3 class="font-extrabold text-sm">Pilihan Menu Lengkap</h3>
            <p class="text-xs text-gray-500 mt-1">Mie, dimsum, minuman, dan paket hemat.</p>
        </div>
        <div>
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-3xl mb-3">📱</div>
            <h3 class="font-extrabold text-sm">Pesan Mudah</h3>
            <p class="text-xs text-gray-500 mt-1">Tinggal klik, makanan langsung disiapkan.</p>
        </div>
        <div class="hidden md:flex justify-end">
            <span class="font-hand text-4xl text-ink rotate-[-6deg] leading-tight text-right">Gacoan<br>Selalu<br>di Hati</span>
        </div>
    </div>
</section>

<!-- CARA PESAN -->
<section id="cara-pesan" class="max-w-[90rem] mx-auto px-4 pb-14 scroll-mt-20">
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 md:p-8">
        <h2 class="text-xl md:text-2xl font-extrabold mb-5">Cara Pesan</h2>
        <div class="grid md:grid-cols-3 gap-5 text-sm">
            <div class="flex gap-3">
                <span class="w-8 h-8 shrink-0 rounded-full bg-brand font-extrabold flex items-center justify-center">1</span>
                <p><b>Pilih menu</b><br><span class="text-gray-600">Tambahkan menu favoritmu ke keranjang.</span></p>
            </div>
            <div class="flex gap-3">
                <span class="w-8 h-8 shrink-0 rounded-full bg-brand font-extrabold flex items-center justify-center">2</span>
                <p><b>Checkout</b><br><span class="text-gray-600">Isi nama dan nomor meja, lalu dapatkan QR Code.</span></p>
            </div>
            <div class="flex gap-3">
                <span class="w-8 h-8 shrink-0 rounded-full bg-brand font-extrabold flex items-center justify-center">3</span>
                <p><b>Bayar di kasir</b><br><span class="text-gray-600">Tunjukkan QR Code, pesananmu langsung diproses.</span></p>
            </div>
        </div>
    </div>
</section>

<!-- Tombol keranjang melayang -->
<button id="floating-cart" type="button" onclick="openCart()"
        class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 z-30 bg-ink text-white rounded-full pl-5 pr-2 py-2 shadow-xl items-center gap-4 text-sm">
    <span><b id="float-count">0</b> item &middot; <b id="float-total">Rp 0</b></span>
    <span class="bg-brand text-ink font-extrabold rounded-full px-4 py-2 text-xs">Lihat Keranjang</span>
</button>

<!-- Panel keranjang -->
<div id="cart-overlay" onclick="closeCart()" class="hidden fixed inset-0 bg-black/50 z-40"></div>
<aside id="cart-panel" class="fixed top-0 right-0 h-full w-full max-w-sm bg-white z-50 shadow-2xl translate-x-full transition-transform duration-300 flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b">
        <h2 class="text-lg font-extrabold">Keranjang Pesanan</h2>
        <button type="button" onclick="closeCart()" class="w-8 h-8 rounded-full hover:bg-gray-100 text-xl leading-none" aria-label="Tutup">&times;</button>
    </div>

    <div class="flex-1 overflow-y-auto px-5 py-4">
        <div id="cart-items" class="space-y-2 text-xs mb-5">
            <p class="text-gray-400 text-center py-6">Keranjang masih kosong.</p>
        </div>

        <form id="checkout-form" action="{{ route('order.checkout') }}" method="POST" onsubmit="return validateCheckout()">
            @csrf
            <input type="hidden" name="cart" id="cart-input">

            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Nama Pemesan <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" required placeholder="Contoh: Budi"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-brand focus:border-brand outline-none">
            </div>

            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Tipe Pesanan <span class="text-red-500">*</span></label>
                <select name="order_type" id="order_type" onchange="toggleTableInput()"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-brand focus:border-brand outline-none">
                    <option value="dine_in">Makan di Tempat (Dine In)</option>
                    <option value="takeaway">Bawa Pulang (Takeaway)</option>
                </select>
            </div>

            <div class="mb-2" id="table-input-group">
                <label class="block text-xs font-bold mb-1">Nomor Meja <span class="text-red-500">*</span></label>
                <input type="text" name="table_number" placeholder="Contoh: 12"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-brand focus:border-brand outline-none">
            </div>
        </form>
    </div>

    <div class="border-t px-5 py-4">
        <div class="flex justify-between font-extrabold mb-3">
            <span>Total</span>
            <span id="cart-total">Rp 0</span>
        </div>
        <button type="submit" form="checkout-form" id="btn-checkout" disabled
                class="w-full bg-gray-300 text-white font-extrabold py-3 rounded-lg text-sm cursor-not-allowed">
            Checkout dan Dapatkan QR Code
        </button>
    </div>
</aside>

<script>
    let cart = [];
    let activeCat = 'all';

    /* ---------- Panel keranjang ---------- */
    function openCart() {
        document.getElementById('cart-overlay').classList.remove('hidden');
        document.getElementById('cart-panel').classList.remove('translate-x-full');
    }
    function closeCart() {
        document.getElementById('cart-overlay').classList.add('hidden');
        document.getElementById('cart-panel').classList.add('translate-x-full');
    }

    /* ---------- Keranjang ---------- */
    function addToCart(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price: Number(price), quantity: 1 });
        }
        renderCart();
    }

    function changeQuantity(id, amount) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.quantity += amount;
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        renderCart();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const btn = document.getElementById('btn-checkout');
        let total = 0;
        let count = 0;

        if (cart.length === 0) {
            container.innerHTML = '<p class="text-gray-400 text-center py-6">Keranjang masih kosong.</p>';
            btn.disabled = true;
            btn.className = 'w-full bg-gray-300 text-white font-extrabold py-3 rounded-lg text-sm cursor-not-allowed';
        } else {
            container.innerHTML = '';
            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                count += item.quantity;
                container.innerHTML += `
                    <div class="flex justify-between items-center bg-yellow-50 p-2.5 rounded-xl border border-yellow-100">
                        <div class="flex-grow pr-2">
                            <p class="font-bold text-gray-800">${escapeHtml(item.name)}</p>
                            <p class="font-bold text-gray-600">Rp ${subtotal.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="changeQuantity(${item.id}, -1)" class="w-6 h-6 bg-white border border-gray-300 rounded-md font-bold hover:bg-gray-100">-</button>
                            <span class="font-bold text-sm w-4 text-center">${item.quantity}</span>
                            <button type="button" onclick="changeQuantity(${item.id}, 1)" class="w-6 h-6 bg-brand text-ink rounded-md font-bold hover:bg-brandDark">+</button>
                        </div>
                    </div>`;
            });
            btn.disabled = false;
            btn.className = 'w-full bg-brand hover:bg-brandDark text-ink font-extrabold py-3 rounded-lg text-sm shadow transition cursor-pointer';
        }

        const totalText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('cart-total').innerText = totalText;
        document.getElementById('cart-input').value = JSON.stringify(cart);

        const badge = document.getElementById('cart-count');
        if (badge) badge.innerText = count;

        document.getElementById('float-count').innerText = count;
        document.getElementById('float-total').innerText = totalText;
        const floating = document.getElementById('floating-cart');
        floating.classList.toggle('hidden', count === 0);
        floating.classList.toggle('flex', count > 0);
    }

    function toggleTableInput() {
        const type = document.getElementById('order_type').value;
        document.getElementById('table-input-group').style.display = type === 'dine_in' ? 'block' : 'none';
    }

    function validateCheckout() {
        if (cart.length === 0) {
            alert('Keranjang masih kosong!');
            return false;
        }
        return true;
    }

    /* ---------- Filter kategori + pencarian ---------- */
    function applyFilter() {
        const keyword = document.getElementById('search').value.trim().toLowerCase();
        let visible = 0;
        const cards = document.querySelectorAll('.menu-card');

        cards.forEach(card => {
            const show = (activeCat === 'all' || card.dataset.cat === activeCat)
                      && card.dataset.name.includes(keyword);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('no-result').classList.toggle('hidden', visible > 0 || cards.length === 0);
    }

    function filterCategory(cat) {
        activeCat = String(cat);
        document.querySelectorAll('.cat-btn').forEach(btn => {
            const active = btn.dataset.cat === activeCat;
            const circle = btn.querySelector('.cat-circle');
            const label = btn.querySelector('.cat-label');
            circle.classList.toggle('bg-brand', active);
            circle.classList.toggle('bg-gray-100', !active);
            label.classList.toggle('text-brandDark', active);
            label.classList.toggle('font-bold', active);
            label.classList.toggle('text-gray-700', !active);
            label.classList.toggle('font-medium', !active);
        });
        applyFilter();
    }

    function resetFilter() {
        document.getElementById('search').value = '';
        filterCategory('all');
    }

    function quickSearch(name) {
        document.getElementById('search').value = name;
        applyFilter();
        document.getElementById('menu').scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('search').addEventListener('input', applyFilter);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });
    toggleTableInput();
    renderCart();
</script>
@endsection