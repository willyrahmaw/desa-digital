@php
    try {
        $loginSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        $namaDesa = $loginSettings['nama_desa'] ?? 'Desa Candraloka';
        $kabupaten = $loginSettings['kabupaten'] ?? '';
    } catch(\Exception $e) {
        $loginSettings = [];
        $namaDesa = 'Desa Candraloka';
        $kabupaten = '';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem — E-Desa {{ $namaDesa }}</title>

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
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50 selection:bg-emerald-500 selection:text-white" x-data="{ showPassword: false, showForgotModal: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (50% Desktop): Background Ilustrasi & Teks Murni               -->
        <!-- ========================================================================= -->
        <div class="relative hidden lg:flex lg:w-1/2 min-h-screen overflow-hidden bg-slate-950">
            <!-- Background Image -->
            <img src="{{ asset('bg.png') }}" 
                 alt="Sistem Informasi Desa" 
                 class="absolute inset-0 h-full w-full object-cover object-center transform scale-105 transition-transform duration-1000 ease-out hover:scale-100">

            <!-- Deep Ambient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-emerald-950/30"></div>
            <div class="absolute inset-0 bg-emerald-950/15 backdrop-blur-[0.5px]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-950/30"></div>

            <!-- Content: Hanya Teks Saja -->
            <div class="relative z-10 flex flex-col justify-end w-full p-12 lg:p-16 text-white space-y-2">
                <h3 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Sistem Informasi Desa
                </h3>
                <p class="text-base lg:text-lg text-slate-200 font-medium leading-relaxed">
                    Melayani Desa dengan Lebih Cepat, Modern, dan Terintegrasi
                </p>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (50% Desktop): Form Login Modern & Premium                    -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col justify-between min-h-screen p-6 sm:p-10 lg:p-16 bg-white overflow-y-auto">
            
            <!-- Top Bar Navigation -->
            <div class="flex items-center justify-between w-full max-w-sm mx-auto">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition-colors group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Ke Beranda</span>
                </a>

                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-600 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Sistem Aktif
                </span>
            </div>

            <!-- Form Card Wrapper -->
            <div class="w-full max-w-sm mx-auto my-auto py-8">
                
                <!-- Brand / Logo & Greetings -->
                <div class="space-y-3 mb-8">
                    <div class="inline-flex p-3 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-xs">
                        @if(!empty($loginSettings['logo_desa']))
                            <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo {{ $namaDesa }}" class="h-8 w-8 object-contain">
                        @else
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Selamat Datang</span>
                            <span class="inline-block hover:rotate-12 transition-transform cursor-default">👋</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            Silakan masukkan kredensial akun Anda untuk masuk ke sistem.
                        </p>
                    </div>
                </div>

                <!-- Flash Notifications -->
                @if (session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-xs">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5 shadow-xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="space-y-4" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email / Username -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Email atau Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="text" 
                                   autocomplete="username" 
                                   required 
                                   value="{{ old('email') }}"
                                   placeholder="nama@desa.id"
                                   class="block w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            <button type="button" 
                                    @click="showForgotModal = true"
                                    class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline transition-colors">
                                Lupa Password?
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   autocomplete="current-password" 
                                   required 
                                   placeholder="••••••••"
                                   class="block w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                            
                            <!-- Show/Hide Button -->
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors cursor-pointer"
                                    title="Tampilkan / Sembunyikan Password">
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

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none group">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/30 accent-emerald-600 transition-colors cursor-pointer">
                            <span class="text-xs font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-700/25 focus:ring-4 focus:ring-emerald-500/20 focus:outline-none transition-all duration-150 cursor-pointer active:scale-[0.99]">
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

            </div>

            <!-- Footer Bottom -->
            <div class="w-full max-w-sm mx-auto pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span>© {{ date('Y') }} {{ $namaDesa }}</span>
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Aman & Terenkripsi
                </span>
            </div>
        </div>

    </div>

    <!-- Modal Lupa Password -->
    <div x-show="showForgotModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div @click.away="showForgotModal = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200 space-y-4">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Pemulihan Kata Sandi</h4>
                        <span class="text-[11px] text-slate-500">Prosedur Keamanan Sistem Desa</span>
                    </div>
                </div>
                <button type="button" @click="showForgotModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-2 text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                <p>
                    Reset kata sandi akun dilakukan secara terpusat oleh <strong>Administrator</strong> kantor desa untuk menjaga keamanan data warga.
                </p>
                <p>
                    Silakan hubungi Administrator atau Sekdes di Kantor Desa untuk pembaruan kata sandi.
                </p>
            </div>

            <div class="flex justify-end pt-1">
                <button type="button" 
                        @click="showForgotModal = false"
                        class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</body>
</html>
