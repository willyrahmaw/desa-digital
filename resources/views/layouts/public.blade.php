<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Website Resmi Pemerintah Desa — Portal Informasi & Layanan Publik')</title>
    <meta name="description" content="@yield('meta_description', 'Portal Resmi Pemerintah Desa Digital. Pusat layanan administrasi kependudukan, transparansi anggaran APBDes, dan informasi publik.')">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0F172A">

    {{-- Fonts: Plus Jakarta Sans (Civic Standard) & Lora (Editorial Accent) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS Engine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', '-apple-system', 'sans-serif'],
                        serif: ['Lora', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- TomSelect Searchable Dropdown CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    
    <!-- AOS (Animate On Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Public Portal CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @elseif(!empty($settings['logo_desa']))
        <link rel="icon" href="{{ asset('storage/' . $settings['logo_desa']) }}">
    @endif
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] flex flex-col min-h-screen antialiased selection:bg-blue-600 selection:text-white">

    @php
        $navItems = [
            ['route' => 'public.home', 'label' => 'Beranda', 'active' => request()->routeIs('public.home')],
            ['route' => 'public.profil', 'label' => 'Profil Desa', 'active' => request()->routeIs('public.profil')],
            ['route' => 'public.layanan', 'label' => 'Layanan Publik', 'active' => request()->routeIs('public.layanan')],
            ['route' => 'public.berita.index', 'label' => 'Kabar Desa', 'active' => request()->routeIs('public.berita.*')],
            ['route' => 'public.agenda', 'label' => 'Agenda', 'active' => request()->routeIs('public.agenda')],
            ['route' => 'public.umkm', 'label' => 'UMKM & Potensi', 'active' => request()->routeIs('public.umkm')],
            ['route' => 'public.galeri', 'label' => 'Galeri', 'active' => request()->routeIs('public.galeri')],
            ['route' => 'public.pengaduan.index', 'label' => 'Pengaduan', 'active' => request()->routeIs('public.pengaduan.*')],
        ];
    @endphp

    {{-- ── 1. GLOBAL FLOATING / FIXED HEADER WRAPPER ─────────────────────── --}}
    <div x-data="{ 
            mobileOpen: false,
            isScrolled: false,
            init() {
                this.isScrolled = window.pageYOffset > 30;
                window.addEventListener('scroll', () => {
                    this.isScrolled = window.pageYOffset > 30;
                }, { passive: true });
            }
         }"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

        {{-- Top Institutional Sub-Bar --}}
        <div x-show="!isScrolled"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="{{ request()->routeIs('public.home') ? 'bg-black/35 backdrop-blur-xs text-slate-200 border-b border-white/10' : 'bg-slate-100/90 backdrop-blur-xs text-slate-600 border-b border-slate-200/80' }} text-xs py-1.5 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
                {{-- Left: Official Info & Operational Hours --}}
                <div class="flex items-center gap-4 flex-wrap justify-center sm:justify-start text-[11px] sm:text-xs">
                    <span class="inline-flex items-center gap-1.5 font-semibold">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('public.home') ? 'text-blue-400' : 'text-blue-600' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="current-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                    </span>
                    <span class="hidden md:inline-block opacity-40">|</span>
                    <span class="hidden md:inline-flex items-center gap-1.5 font-medium opacity-90">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('public.home') ? 'text-emerald-400' : 'text-emerald-600' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Jam Pelayanan: Senin – Jumat (08:00 – 15:00 WIB)
                    </span>
                </div>

                {{-- Right: Contact / Aduan Warga --}}
                <div class="flex items-center gap-4 text-[11px] font-bold">
                    <a href="{{ route('public.pengaduan.index') }}" class="inline-flex items-center gap-1.5 {{ request()->routeIs('public.home') ? 'text-amber-300 hover:text-amber-200' : 'text-slate-700 hover:text-blue-700' }} transition-colors">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Lapor / Aduan Warga</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Navigation Bar (Transparent at top, Solid white on scroll) --}}
        <header id="main-navbar"
                :class="(isScrolled || mobileOpen) ? 'bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-md' : '{{ request()->routeIs('public.home') ? 'bg-gradient-to-b from-black/60 via-black/25 to-transparent border-b border-transparent' : 'bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs' }}'"
                class="transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">

                    {{-- Official Brand Identity --}}
                    <a href="{{ route('public.home') }}" class="flex items-center gap-3.5 group shrink-0">
                        @if(!empty($settings['logo_desa']))
                            <div class="w-12 h-12 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <img src="{{ asset('storage/' . $settings['logo_desa']) }}" alt="Logo {{ $settings['nama_desa'] ?? 'Desa' }}" class="max-w-full max-h-full object-contain drop-shadow-xs">
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-700 to-indigo-900 border border-blue-400/30 flex items-center justify-center text-white shadow-md shrink-0 group-hover:scale-[1.02] transition-transform">
                                <svg class="w-6 h-6 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 4 4.5.5-3.25 3.25.75 4.5-4-2.25-4 2.25.75-4.5-3.25-3.25 4.5-.5z" fill="currentColor"/>
                                </svg>
                            </div>
                        @endif
                        <div class="leading-tight">
                            <span :class="(isScrolled || mobileOpen || !{{ request()->routeIs('public.home') ? 'true' : 'false' }}) ? 'text-blue-700' : 'text-blue-300'"
                                  class="text-[10px] font-extrabold uppercase tracking-widest block transition-colors">
                                PEMERINTAH DESA
                            </span>
                            <span :class="(isScrolled || mobileOpen || !{{ request()->routeIs('public.home') ? 'true' : 'false' }}) ? 'text-slate-900' : 'text-white'"
                                  class="text-base sm:text-lg font-extrabold tracking-tight block transition-colors">
                                {{ $settings['nama_desa'] ?? 'DESA DIGITAL' }}
                            </span>
                        </div>
                    </a>

                    {{-- Desktop Navigation Links --}}
                    <nav class="hidden xl:flex items-center gap-6">
                        @foreach($navItems as $nav)
                            <a href="{{ route($nav['route']) }}"
                               :class="(isScrolled || mobileOpen || !{{ request()->routeIs('public.home') ? 'true' : 'false' }}) ? 
                                       '{{ $nav['active'] ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-slate-700 hover:text-blue-700' }}' : 
                                       '{{ $nav['active'] ? 'text-white font-bold border-b-2 border-blue-400 drop-shadow' : 'text-slate-100 hover:text-white drop-shadow' }}'"
                               class="text-xs font-semibold py-2 transition-colors duration-150">
                                {{ $nav['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- Header Actions --}}
                    <div class="hidden lg:flex items-center gap-3">
                        <a href="{{ route('public.layanan') }}"
                           :class="(isScrolled || mobileOpen || !{{ request()->routeIs('public.home') ? 'true' : 'false' }}) ? 
                                   'border-slate-300/80 text-slate-700 bg-white hover:bg-slate-50' : 
                                   'border-white/30 text-white bg-white/15 hover:bg-white/25 backdrop-blur-md'"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl border transition-all">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Syarat Layanan</span>
                        </a>

                        <a href="{{ route('public.pengaduan.index') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-blue-700 text-white hover:bg-blue-800 shadow-md transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            <span>Pengaduan NIK</span>
                        </a>
                    </div>

                    {{-- Mobile Hamburger Button --}}
                    <button x-on:click="mobileOpen = !mobileOpen" type="button"
                            :class="(isScrolled || mobileOpen || !{{ request()->routeIs('public.home') ? 'true' : 'false' }}) ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/10'"
                            class="xl:hidden p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="Buka Menu Navigasi">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display:none"/>
                        </svg>
                    </button>

                </div>
            </div>

            {{-- Mobile Navigation Drawer --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="xl:hidden bg-white border-t border-slate-200 px-4 pt-3 pb-6 space-y-1.5 shadow-xl"
                 style="display:none">
                @foreach($navItems as $nav)
                    <a href="{{ route($nav['route']) }}"
                       class="block px-3.5 py-2.5 rounded-lg text-xs font-bold transition-colors {{ $nav['active'] ? 'text-blue-700 bg-blue-50' : 'text-slate-700 hover:bg-slate-50' }}">
                        {{ $nav['label'] }}
                    </a>
                @endforeach
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <a href="{{ route('public.layanan') }}"
                       class="block w-full text-center px-4 py-2.5 text-xs font-bold rounded-lg border border-slate-200 text-slate-800 hover:bg-slate-50">
                        Katalog Persyaratan Surat
                    </a>
                    <a href="{{ route('public.pengaduan.index') }}"
                       class="block w-full text-center px-4 py-2.5 text-xs font-bold rounded-lg bg-blue-700 text-white hover:bg-blue-800">
                        Buat Pengaduan Berbasis NIK
                    </a>
                </div>
            </div>
        </header>

    </div>

    {{-- ── 2. MAIN CONTENT CONTAINER ─────────────────────────────────────── --}}
    <main class="flex-grow {{ request()->routeIs('public.home') ? '' : 'pt-28 sm:pt-32' }}">
        @yield('content')
    </main>

    {{-- ── 4. OFFICIAL INSTITUTIONAL FOOTER ───────────────────────────────── --}}
    <footer class="bg-[#0B132B] text-white border-t border-slate-800/90 pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">

                {{-- Column 1: Institutional Identity (5 cols) --}}
                <div class="lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-700/80 border border-blue-400/30 flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 4 4.5.5-3.25 3.25.75 4.5-4-2.25-4 2.25.75-4.5-3.25-3.25 4.5-.5z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest block">PORTAL RESMI</span>
                            <span class="text-base font-black text-white tracking-tight block">{{ $settings['nama_desa'] ?? 'PEMERINTAH DESA DIGITAL' }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Sistem Informasi & Pelayanan Administrasi Terpadu Pemerintah Desa. Menyajikan layanan kependudukan prima, transparansi pengelolaan APBDes, dan keterbukaan informasi publik yang akuntabel.
                    </p>
                    <div class="pt-2 text-slate-400 text-xs flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Sistem Aktif & Terverifikasi Resmi</span>
                    </div>
                </div>

                {{-- Column 2: Pelayanan Publik (3 cols) --}}
                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-blue-400">Pelayanan Dokumen</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li>
                            <a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <span class="text-blue-500 text-[10px]">▸</span> Surat Keterangan Domisili
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <span class="text-blue-500 text-[10px]">▸</span> Surat Keterangan Tidak Mampu (SKTM)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <span class="text-blue-500 text-[10px]">▸</span> Surat Keterangan Usaha (SKU)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <span class="text-blue-500 text-[10px]">▸</span> Pengantar Akta Kelahiran & Kematian
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.pengaduan.index') }}" class="hover:text-white transition-colors flex items-center gap-1.5 font-semibold text-blue-300">
                                <span class="text-amber-400 text-[10px]">▸</span> Pengaduan & Aspirasi Warga (NIK)
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Column 3: Transparansi & Profil (2 cols) --}}
                <div class="lg:col-span-2 space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-blue-400">Tata Kelola & Info</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('public.profil') }}" class="hover:text-white transition-colors">Visi & Misi Desa</a></li>
                        <li><a href="{{ route('public.profil') }}" class="hover:text-white transition-colors">Struktur Organisasi</a></li>
                        <li><a href="{{ route('public.home') }}#apbdes" class="hover:text-white transition-colors">Transparansi APBDes</a></li>
                        <li><a href="{{ route('public.berita.index') }}" class="hover:text-white transition-colors">Kabar & Pengumuman</a></li>
                        <li><a href="{{ route('public.umkm') }}" class="hover:text-white transition-colors">Direktori UMKM Desa</a></li>
                    </ul>
                </div>

                {{-- Column 4: Kontak Kantor (3 cols) --}}
                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-blue-400">Kontak Kantor Desa</h4>
                    <div class="text-xs text-slate-300 space-y-2.5">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $settings['alamat_kantor'] ?? 'Jl. Raya Utama Desa No. 01, Kecamatan Digital, Kabupaten Smart' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $settings['email_desa'] ?? 'kontak@desadigital.desa.id' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $settings['telepon_desa'] ?? '+62 812-3456-7890' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Bottom Compliance & Copyright Bar --}}
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                <div>
                    &copy; {{ date('Y') }} <strong>Pemerintah {{ $settings['nama_desa'] ?? 'Desa Digital' }}</strong>. Hak Cipta Dilindungi Undang-Undang.
                </div>
                <div class="flex items-center gap-4 text-[11px] text-slate-500">
                    <span>UU Keterbukaan Informasi Publik (UU No. 14/2008)</span>
                    <span>•</span>
                    <a href="{{ route('login') }}" class="hover:text-slate-400">Akses Staf</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- TomSelect JS (Instant Searchable Dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        function initPublicSearchableSelects(root) {
            if (typeof TomSelect === 'undefined') return;
            var container = root || document;
            container.querySelectorAll('select:not(.tomselected):not([data-no-search]):not([name$="_length"])').forEach(function(select) {
                var optCount = select.options ? select.options.length : 0;
                var isExplicit = select.classList.contains('searchable-select') || select.dataset.search === 'true';
                
                if (isExplicit || optCount >= 4) {
                    var placeholder = select.getAttribute('placeholder') || 
                                     (select.options.length && select.options[0].value === '' ? select.options[0].text : 'Pilih opsi atau cari...');
                    try {
                        new TomSelect(select, {
                            create: false,
                            maxOptions: 500,
                            placeholder: placeholder,
                            allowEmptyOption: true,
                            controlInput: optCount >= 5 ? '<input>' : null,
                            render: {
                                no_results: function(data, escape) {
                                    return '<div class="no-results p-2.5 text-xs text-slate-500 text-center font-medium">Data tidak ditemukan</div>';
                                }
                            }
                        });
                    } catch(e) {}
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initPublicSearchableSelects();
        });

        if (window.MutationObserver) {
            var tomObserverTimeout;
            new MutationObserver(function(mutations) {
                clearTimeout(tomObserverTimeout);
                tomObserverTimeout = setTimeout(function() {
                    initPublicSearchableSelects(document);
                }, 50);
            }).observe(document.body, { childList: true, subtree: true });
        }
    </script>

    <!-- AOS (Animate On Scroll) JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    easing: 'ease-out-cubic',
                    once: false,
                    offset: 50,
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
