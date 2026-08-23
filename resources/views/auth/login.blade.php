@php
    try {
        $loginSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        $namaDesa = $loginSettings['nama_desa'] ?? 'Desa Digital';
    } catch(\Exception $e) {
        $loginSettings = [];
        $namaDesa = 'Desa Digital';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Desa</title>

    @if(!empty($loginSettings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $loginSettings['favicon']) }}">
    @elseif(!empty($loginSettings['logo_desa']))
        <link rel="icon" href="{{ asset('storage/' . $loginSettings['logo_desa']) }}">
    @endif

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-white" x-data="{ showPassword: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================== -->
        <!-- SISI KIRI: 50% LAYAR (GAMBAR & TEKS)       -->
        <!-- ========================================== -->
        <div class="relative hidden lg:flex lg:w-1/2 min-h-screen overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <img src="{{ asset('bg.png') }}" 
                 alt="Sistem Informasi Desa" 
                 class="absolute inset-0 h-full w-full object-cover">

            <!-- Overlay Gradient Gelap Elegan -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>

            <!-- Teks Di Atas Gambar -->
            <div class="relative z-10 flex flex-col justify-end w-full p-12 lg:p-16 text-white space-y-2">
                <h3 class="text-3xl font-extrabold tracking-tight text-white">
                    Sistem Informasi Desa
                </h3>
                <p class="text-base text-slate-200 font-medium">
                    Melayani Desa dengan Lebih Cepat, Modern, dan Terintegrasi
                </p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SISI KANAN: 50% LAYAR (FORM LOGIN)         -->
        <!-- ========================================== -->
        <div class="flex-1 flex flex-col justify-center min-h-screen p-6 sm:p-12 lg:p-16 bg-white">
            <div class="w-full max-w-sm mx-auto space-y-8">
                
                <!-- Header: Logo, Judul & Deskripsi -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        @if(!empty($loginSettings['logo_desa']))
                            <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo Desa" class="h-12 w-12 object-contain">
                        @else
                            <div class="h-11 w-11 rounded-xl bg-emerald-600 text-white font-black text-lg flex items-center justify-center shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Selamat Datang 👋
                        </h1>
                        <p class="text-sm text-slate-500">
                            Silakan masuk ke akun Anda untuk melanjutkan.
                        </p>
                    </div>
                </div>

                <!-- Alert Error -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Login -->
                <form class="space-y-5" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Input Email / Username -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-slate-700">
                            Email atau Username
                        </label>
                        <input id="email" 
                               name="email" 
                               type="text" 
                               autocomplete="username" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="Masukkan email atau username"
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none transition-all">
                    </div>

                    <!-- Input Password dengan Show/Hide -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   autocomplete="current-password" 
                                   required 
                                   placeholder="Masukkan password"
                                   class="block w-full rounded-xl border border-slate-300 px-3.5 pr-10 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none transition-all">
                            
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Show/Hide Password">
                                <template x-if="!showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <!-- Checkbox Ingat Saya & Lupa Password -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/30 accent-emerald-600 cursor-pointer">
                            <span class="text-slate-600">Ingat saya</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Silakan hubungi Administrator kantor desa untuk mereset kata sandi akun Anda.')" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Tombol Utama Masuk ke Sistem -->
                    <button type="submit"
                            class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-600/20 focus:outline-none transition-all duration-150 cursor-pointer">
                        Masuk ke Sistem
                    </button>
                </form>

            </div>
        </div>

    </div>

</body>
</html>
