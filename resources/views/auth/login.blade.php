@php
    try {
        $loginSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        $namaDesa = $loginSettings['nama_desa'] ?? 'Desa Candraloka';
        $kabupaten = $loginSettings['kabupaten'] ?? '';
        $mottoDesa = $loginSettings['motto_desa'] ?? 'Melayani Desa dengan Lebih Cepat, Modern, dan Terintegrasi';
    } catch(\Exception $e) {
        $loginSettings = [];
        $namaDesa = 'Desa Candraloka';
        $kabupaten = '';
        $mottoDesa = 'Melayani Desa dengan Lebih Cepat, Modern, dan Terintegrasi';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-emerald-500 selection:text-white" x-data="{ showPassword: false, showForgotModal: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (50% Desktop): Background Ilustrasi, Overlay & Branding        -->
        <!-- ========================================================================= -->
        <div class="relative hidden lg:flex lg:w-1/2 min-h-screen overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <img src="{{ asset('bg.png') }}" 
                 alt="Background Sistem Informasi Desa" 
                 class="absolute inset-0 h-full w-full object-cover object-center transform scale-105 transition-transform duration-1000 ease-out hover:scale-100">

            <!-- Elegant Multi-Layer Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-emerald-950/75 to-slate-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-emerald-900/25 backdrop-blur-[0.5px]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-950/40"></div>

            <!-- Content Over Image -->
            <div class="relative z-10 flex flex-col justify-between w-full p-12 lg:p-16 text-white">
                
                <!-- Top Brand Tag -->
                <div class="flex items-center gap-3">
                    @if(!empty($loginSettings['logo_desa']))
                        <div class="p-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-lg">
                            <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo {{ $namaDesa }}" class="h-10 w-10 object-contain">
                        </div>
                    @else
                        <div class="h-11 w-11 rounded-xl bg-emerald-600/90 backdrop-blur-md text-white font-black text-lg flex items-center justify-center border border-emerald-400/40 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-300 block">Pemerintah Desa</span>
                        <h2 class="text-base font-extrabold tracking-tight text-white">{{ $namaDesa }} {{ $kabupaten ? '• ' . $kabupaten : '' }}</h2>
                    </div>
                </div>

                <!-- Center/Bottom Main Hero Title -->
                <div class="space-y-6 max-w-lg">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-emerald-300 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Portal Resmi Tata Kelola Pemerintahan Desa
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            Sistem Informasi Desa
                        </h3>
                        <p class="text-base lg:text-lg font-medium text-emerald-100/90 leading-relaxed">
                            Melayani Desa dengan Lebih Cepat, Modern, dan Terintegrasi
                        </p>
                    </div>

                    <!-- Institutional Feature Pills -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-3.5 py-2.5 rounded-xl border border-white/15 text-xs text-white">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="font-medium">Enkripsi Data Kependudukan (UU PDP)</span>
                        </div>
                        <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-3.5 py-2.5 rounded-xl border border-white/15 text-xs text-white">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="font-medium">Layanan Surat Digital Cepat</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <div class="pt-6 border-t border-white/15 flex items-center justify-between text-xs text-emerald-200/75">
                    <span>Standar Tata Naskah Dinas Permendagri</span>
                    <span>v2.5 • Terintegrasi</span>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (50% Desktop, Full Width Mobile): Form Login Modern & Clean   -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col justify-between min-h-screen p-6 sm:p-10 lg:p-16 bg-white overflow-y-auto">
            
            <!-- Top Action Header -->
            <div class="flex items-center justify-between w-full max-w-md mx-auto">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition-colors group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda Portal</span>
                </a>

                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Koneksi Aman (SSL)
                </span>
            </div>

            <!-- Main Form Card Container -->
            <div class="w-full max-w-md mx-auto my-auto py-8">
                
                <!-- Mobile Header Banner (Only visible on screens < lg) -->
                <div class="lg:hidden mb-8 text-center space-y-3">
                    <div class="inline-flex p-3 bg-emerald-50 rounded-2xl border border-emerald-200 shadow-xs">
                        @if(!empty($loginSettings['logo_desa']))
                            <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo {{ $namaDesa }}" class="h-12 w-12 object-contain">
                        @else
                            <div class="h-12 w-12 rounded-xl bg-emerald-600 text-white font-bold text-xl flex items-center justify-center shadow-xs">
                                ED
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">E-Desa {{ $namaDesa }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Sistem Informasi & Tata Kelola Administrasi Desa</p>
                    </div>
                </div>

                <!-- Form Header -->
                <div class="space-y-2 mb-8">
                    <div class="hidden lg:flex items-center gap-3 mb-4">
                        @if(!empty($loginSettings['logo_desa']))
                            <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo {{ $namaDesa }}" class="h-10 w-10 object-contain">
                        @else
                            <div class="h-10 w-10 rounded-xl bg-emerald-600 text-white font-black text-base flex items-center justify-center shadow-xs">
                                ED
                            </div>
                        @endif
                        <div>
                            <span class="text-[11px] font-extrabold tracking-wider text-emerald-700 uppercase">Panel Masuk Aparatur</span>
                            <span class="text-xs font-semibold text-slate-500 block">{{ $namaDesa }}</span>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Selamat Datang</span>
                        <span class="inline-block transform origin-bottom-right hover:rotate-12 transition-transform cursor-default">👋</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed">
                        Masukkan email atau akun terdaftar Anda untuk mengakses dasbor pengelolaan data dan pelayanan surat desa.
                    </p>
                </div>

                <!-- Flash Alert Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="space-y-1">
                            <p class="font-bold text-rose-950">Gagal Masuk ke Sistem:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-rose-800">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="space-y-5" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email / Username Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Email atau Nama Pengguna
                        </label>
                        <div class="relative rounded-xl shadow-2xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   placeholder="nama@desa.id"
                                   class="block w-full rounded-xl border border-slate-300 pl-10 pr-4 py-3 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password Field with Interactive Show/Hide -->
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
                        <div class="relative rounded-xl shadow-2xs">
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
                                   class="block w-full rounded-xl border border-slate-300 pl-10 pr-11 py-3 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none transition-all">
                            
                            <!-- Toggle Password Visibility Button -->
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors cursor-pointer"
                                    title="Tampilkan / Sembunyikan Kata Sandi">
                                <template x-if="!showPassword">
                                    <!-- Eye Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </template>
                                <template x-if="showPassword">
                                    <!-- Eye Off Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-600/30 accent-emerald-600 transition-colors cursor-pointer">
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya pada perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-md shadow-emerald-600/25 hover:from-emerald-700 hover:to-emerald-800 hover:shadow-lg hover:shadow-emerald-700/30 focus:ring-4 focus:ring-emerald-600/20 focus:outline-none transition-all duration-200 cursor-pointer active:scale-[0.99]">
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Information Notice Box -->
                <div class="mt-8 p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                    <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Akses Khusus Aparatur Desa
                    </p>
                    <p class="text-xs text-slate-500">
                        Untuk permohonan surat & pengaduan masyarakat umum, silakan gunakan menu layanan di portal publik.
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="w-full max-w-md mx-auto pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2">
                <span>© {{ date('Y') }} {{ $namaDesa }}</span>
                <span>Sistem Informasi Desa Digital</span>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: Lupa Password & Bantuan Akses                                -->
    <!-- ========================================================================= -->
    <div x-show="showForgotModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showForgotModal = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Pemulihan Akun & Sandi</h4>
                        <span class="text-xs text-slate-500">Prosedur Keamanan Sistem Desa</span>
                    </div>
                </div>
                <button type="button" @click="showForgotModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-3 text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200">
                <p>
                    Demi keamanan data kependudukan, reset kata sandi akun perangkat desa dilakukan secara terpusat oleh <strong>Super Administrator</strong> kantor desa.
                </p>
                <div class="pt-2 space-y-1.5">
                    <p class="font-bold text-slate-800">Langkah Pemulihan:</p>
                    <ol class="list-decimal pl-4 space-y-1 text-slate-600">
                        <li>Hubungi Administrator atau Sekdes di Kantor Desa.</li>
                        <li>Verifikasi identitas NIK dan Surat Tugas Anda.</li>
                        <li>Administrator akan menerbitkan sandi baru melalui menu Manajemen Pengguna.</li>
                    </ol>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" 
                        @click="showForgotModal = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors cursor-pointer">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

</body>
</html>
