<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mie Gacoan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        logo: ['"Bebas Neue"', 'Impact', 'sans-serif'],
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
        /* Di layar besar, 1rem mengikuti ukuran layar supaya isi kartu dan teks
           ikut mengecil/membesar bersama foto dan posisinya tetap sama. */
        @media (min-width: 1024px) {
            html { font-size: max(11px, calc(min(100vw, 100vh * 1.4907) / 96)); }
        }
        .swoosh { background: linear-gradient(transparent 80%, #ffc62b 80%, #ffc62b 92%, transparent 92%); }
    </style>
</head>
<body class="min-h-screen bg-[#fff8e6] text-gray-800 font-sans antialiased">

<div class="lg:fixed lg:inset-0 lg:overflow-hidden">
    <div class="relative lg:absolute lg:inset-0">

        <!-- Foto latar (khusus registrasi, tanpa teks sambutan) -->
        <img src="{{ asset('images/login.png') }}"
             alt="Daftar akun pegawai Mie Gacoan"
             class="w-full h-56 sm:h-72 object-cover object-top lg:absolute lg:inset-0 lg:h-full lg:object-fill">

        
        <!-- Kartu registrasi -->
        <div class="relative -mt-8 mx-4 mb-8 sm:mx-auto sm:max-w-lg bg-[#fffdf8] rounded-3xl shadow-xl px-6 py-8
                    lg:absolute lg:m-0 lg:max-w-none lg:left-[53.9%] lg:top-[7.8%] lg:w-[39.6%] lg:h-[84.4%]
                    lg:px-[5.8%] lg:py-0 lg:flex lg:flex-col lg:justify-center lg:shadow-[0_20px_60px_rgba(255,198,43,0.28)]">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex flex-col items-center mb-[1.1rem]" aria-label="Ke halaman menu">
                <span class="bg-gpink text-white font-logo text-[1.3rem] leading-none px-[0.85rem] py-[0.28rem] rounded-full rotate-[-6deg] -mb-[0.3rem] relative z-10">ME</span>
                <span class="font-logo text-[3.1rem] leading-[0.95] text-ink tracking-wide">GACOAN</span>
                <span class="text-[0.68rem] font-semibold text-ink mt-[0.2rem]">&ldquo;Mie Gacoan, Selalu di Hati&rdquo;</span>
            </a>

            <h1 class="text-[1.75rem] font-extrabold text-ink leading-tight">Daftar Akun Baru</h1>
            <p class="text-[0.95rem] text-gray-500 mt-1 mb-[1.1rem]">Isi data di bawah ini untuk membuat akun.</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Nama -->
                <div class="relative">
                    <span class="absolute left-[1.2rem] top-1/2 -translate-y-1/2 text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[1.35rem] h-[1.35rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"/></svg>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap"
                           class="w-full h-[3.4rem] rounded-xl border border-gray-200 bg-white pl-[3.4rem] pr-4 text-[0.92rem] placeholder-gray-400 focus:ring-2 focus:ring-brand focus:border-brand outline-none">
                </div>
                @error('name')
                    <p class="text-red-500 text-[0.72rem] mt-1 font-semibold">{{ $message }}</p>
                @enderror

                <!-- Email -->
                <div class="relative mt-[0.95rem]">
                    <span class="absolute left-[1.2rem] top-1/2 -translate-y-1/2 text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[1.35rem] h-[1.35rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Alamat Email"
                           class="w-full h-[3.4rem] rounded-xl border border-gray-200 bg-white pl-[3.4rem] pr-4 text-[0.92rem] placeholder-gray-400 focus:ring-2 focus:ring-brand focus:border-brand outline-none">
                </div>
                @error('email')
                    <p class="text-red-500 text-[0.72rem] mt-1 font-semibold">{{ $message }}</p>
                @enderror

                <!-- Password -->
                <div class="relative mt-[0.95rem]">
                    <span class="absolute left-[1.2rem] top-1/2 -translate-y-1/2 text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[1.35rem] h-[1.35rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V8a4 4 0 118 0v3"/></svg>
                    </span>
                    <input id="password" type="password" name="password" required placeholder="Password"
                           class="w-full h-[3.4rem] rounded-xl border border-gray-200 bg-white pl-[3.4rem] pr-14 text-[0.92rem] placeholder-gray-400 focus:ring-2 focus:ring-brand focus:border-brand outline-none">
                    <button type="button" onclick="togglePassword('password', this)" aria-label="Tampilkan atau sembunyikan password"
                            class="absolute inset-y-0 right-0 px-[1.1rem] text-ink hover:text-gray-600">
                        <svg class="eye-off w-[1.35rem] h-[1.35rem]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.1A9.6 9.6 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.2 3.9M6.6 6.7A16 16 0 002.5 12S6 18 12 18a9.7 9.7 0 004.1-.9M9.9 9.9a3 3 0 004.2 4.2"/></svg>
                        <svg class="eye-on w-[1.35rem] h-[1.35rem] hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7S2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-[0.72rem] mt-1 font-semibold">{{ $message }}</p>
                @enderror

                <!-- Konfirmasi password -->
                <div class="relative mt-[0.95rem]">
                    <span class="absolute left-[1.2rem] top-1/2 -translate-y-1/2 text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[1.35rem] h-[1.35rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V8a4 4 0 118 0v3M9.5 16l1.8 1.8 3.2-3.4"/></svg>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Konfirmasi Password"
                           class="w-full h-[3.4rem] rounded-xl border border-gray-200 bg-white pl-[3.4rem] pr-14 text-[0.92rem] placeholder-gray-400 focus:ring-2 focus:ring-brand focus:border-brand outline-none">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan atau sembunyikan konfirmasi password"
                            class="absolute inset-y-0 right-0 px-[1.1rem] text-ink hover:text-gray-600">
                        <svg class="eye-off w-[1.35rem] h-[1.35rem]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.1A9.6 9.6 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.2 3.9M6.6 6.7A16 16 0 002.5 12S6 18 12 18a9.7 9.7 0 004.1-.9M9.9 9.9a3 3 0 004.2 4.2"/></svg>
                        <svg class="eye-on w-[1.35rem] h-[1.35rem] hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7S2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>

                <!-- Tombol daftar -->
                <button type="submit"
                        class="w-full h-[3.5rem] mt-[1.2rem] rounded-xl bg-brand hover:bg-brandDark active:scale-[0.99] text-ink font-extrabold text-[1rem] flex items-center justify-center gap-3 shadow-md transition">
                    Daftar
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[1.3rem] h-[1.3rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <!-- atau -->
            <div class="flex items-center gap-4 my-[0.9rem] text-[0.8rem] text-gray-400">
                <span class="flex-1 h-px bg-gray-200"></span>
                atau
                <span class="flex-1 h-px bg-gray-200"></span>
            </div>

            <!-- Google (tampilan saja, belum terhubung) -->
            <button type="button" onclick="alert('Daftar dengan Google belum tersedia.')"
                    class="w-full h-[3.4rem] rounded-xl border border-gray-300 bg-white hover:bg-gray-50 flex items-center justify-center gap-3 font-semibold text-[0.92rem] text-ink transition">
                <svg class="w-[1.35rem] h-[1.35rem]" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Daftar dengan Google
            </button>

            <p class="text-center text-[0.8rem] text-gray-500 mt-[0.9rem]">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-brandDark font-bold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('.eye-on').classList.toggle('hidden', !show);
        btn.querySelector('.eye-off').classList.toggle('hidden', show);
    }
</script>
</body>
</html>