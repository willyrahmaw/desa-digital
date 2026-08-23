@extends('layouts.public')

@section('title', 'Website Resmi ' . ($settings['nama_desa'] ?? 'Pemerintah Desa Digital') . ' — Portal Informasi & Layanan Publik')
@section('meta_description', 'Portal Resmi Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '. Melayani administrasi kependudukan, transparansi anggaran APBDes, kabar desa, dan pengaduan warga.')

@section('content')

{{-- ── 1. CLEAN & ELEGANT FULL-WIDTH HERO SLIDER ───────────── --}}
<section class="bg-[#0A1128] relative border-b border-slate-800 overflow-hidden">
    <div x-data="{
        current: 0,
        total: {{ count($hero_slides) > 0 ? count($hero_slides) : 1 }},
        timer: null,
        touchStartX: 0,
        touchEndX: 0,
        init() {
            this.startAutoplay();
        },
        startAutoplay() {
            this.stopAutoplay();
            if (this.total > 1) {
                this.timer = setInterval(() => {
                    this.next();
                }, 5500);
            }
        },
        stopAutoplay() {
            if (this.timer) clearInterval(this.timer);
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        },
        goTo(index) {
            this.current = index;
        },
        handleTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },
        handleTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            if (this.touchStartX - this.touchEndX > 45) {
                this.next();
            } else if (this.touchEndX - this.touchStartX > 45) {
                this.prev();
            }
        }
    }" 
    x-on:mouseenter="stopAutoplay()" 
    x-on:mouseleave="startAutoplay()"
    x-on:touchstart="handleTouchStart($event)"
    x-on:touchend="handleTouchEnd($event)"
    class="relative w-full h-screen min-h-[640px] overflow-hidden select-none">

        {{-- Slider Items --}}
        @forelse($hero_slides as $index => $slide)
            <div x-show="current === {{ $index }}"
                 x-transition:enter="transition-all duration-700 ease-out"
                 x-transition:enter-start="opacity-0 scale-[1.02]"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition-all duration-500 ease-in"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full"
                 style="{{ $index === 0 ? '' : 'display: none;' }}">
                
                {{-- Background Image --}}
                @if(!empty($slide['gambar']))
                    <img src="{{ asset('storage/' . $slide['gambar']) }}" alt="{{ $slide['judul'] }}" class="w-full h-full object-cover animate-subtle-zoom">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[radial-gradient(#1e3a8a_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
                    </div>
                @endif

                {{-- Cinematic Gradient Overlay (Dark bottom & left for sharp text readability) --}}
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1128] via-[#0A1128]/70 to-slate-950/40"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#0A1128]/95 via-[#0A1128]/60 to-transparent max-w-4xl"></div>

                {{-- Content Over Image --}}
                <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center pt-20 pb-20 text-white z-10">
                    
                    <div class="space-y-4 max-w-3xl">
                        
                        {{-- Institutional Badge --}}
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-950/80 backdrop-blur-md border border-blue-400/30 text-blue-200 text-xs font-extrabold uppercase tracking-wider shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $slide['kategori'] ?? 'INFORMASI RESMI' }}</span>
                            <span class="text-blue-300/60">• {{ $settings['nama_desa'] ?? 'DESA DIGITAL' }}</span>
                        </div>

                        {{-- Slide Title --}}
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-md">
                            {{ $slide['judul'] }}
                        </h1>

                        {{-- Slide Subtitle --}}
                        @if(!empty($slide['subjudul']))
                            <p class="text-sm sm:text-base text-slate-200 font-normal leading-relaxed drop-shadow line-clamp-3 max-w-2xl">
                                {{ $slide['subjudul'] }}
                            </p>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center gap-3 pt-3">
                            @if(!empty($slide['link']))
                                <a href="{{ $slide['link'] }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 text-xs sm:text-sm font-bold rounded-xl bg-blue-600 hover:bg-blue-500 text-white transition-all shadow-lg shadow-blue-900/40 hover:-translate-y-0.5 active:scale-95">
                                    <span>{{ $slide['button_text'] ?? 'Lihat Selengkapnya' }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endif

                            <a href="{{ route('public.layanan') }}" class="inline-flex items-center gap-2 px-6 py-3.5 text-xs sm:text-sm font-bold rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/25 backdrop-blur-md transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Layanan Administrasi</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="absolute inset-0 w-full h-full bg-slate-900 flex items-center justify-center">
                <div class="text-center text-white px-4 space-y-3 max-w-2xl">
                    <span class="px-3.5 py-1.5 rounded-full bg-blue-900/60 border border-blue-400/30 text-xs font-bold uppercase tracking-wider">
                        PEMERINTAH DESA DIGITAL
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold">Selamat Datang di Portal Resmi Desa</h1>
                    <p class="text-xs sm:text-sm text-slate-300">{{ $settings['nama_kecamatan'] ?? 'Kecamatan Digital' }}, {{ $settings['nama_kabupaten'] ?? 'Kabupaten Smart' }}</p>
                </div>
            </div>
        @endforelse

        {{-- Next / Prev Navigation Controls --}}
        @if(count($hero_slides) > 1)
            <button x-on:click="prev()" type="button" aria-label="Slide Sebelumnya"
                    class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-blue-600 text-white flex items-center justify-center transition-all border border-white/20 backdrop-blur-md shadow-lg hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button x-on:click="next()" type="button" aria-label="Slide Selanjutnya"
                    class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-blue-600 text-white flex items-center justify-center transition-all border border-white/20 backdrop-blur-md shadow-lg hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Slider Dots & Slide Indicator --}}
            <div class="absolute bottom-8 sm:bottom-10 left-1/2 -translate-x-1/2 z-20 flex items-center gap-3 bg-black/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 shadow-xl">
                <div class="flex items-center gap-2">
                    @foreach($hero_slides as $index => $s)
                        <button x-on:click="goTo({{ $index }})" 
                                type="button"
                                aria-label="Menuju Slide {{ $index + 1 }}"
                                :class="current === {{ $index }} ? 'w-7 bg-blue-400' : 'w-2 bg-white/40 hover:bg-white/70'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
                <span class="text-[11px] font-mono text-slate-300 font-bold border-l border-white/20 pl-3">
                    <span x-text="String(current + 1).padStart(2, '0')">01</span> / <span>{{ str_pad(count($hero_slides), 2, '0', STR_PAD_LEFT) }}</span>
                </span>
            </div>

            {{-- Subtle Scroll Down Indicator --}}
            <a href="#quick-access"
               aria-label="Scroll ke konten"
               class="hidden sm:flex absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex-col items-center gap-1 text-white/70 hover:text-white transition-colors animate-float-slow text-[10px] font-bold tracking-widest uppercase">
                <span>Jelajahi</span>
                <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
        @endif

    </div>
</section>


{{-- ── 2. QUICK CIVIC ACCESS BAR (4 CARDS) ──────────────────────────────── --}}
<section id="quick-access" class="py-10 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Layanan Surat --}}
        <a href="{{ route('public.layanan') }}" data-aos="fade-up" data-aos-delay="0" class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-blue-300 hover-lift transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 flex items-center justify-center shrink-0 group-hover:bg-blue-700 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Pelayanan Surat</h3>
                <p class="text-xs text-slate-500 mt-0.5">Katalog persyaratan domisili, SKTM, SKU & perizinan.</p>
            </div>
        </a>

        {{-- Card 2: Transparansi APBDes --}}
        <a href="#apbdes" data-aos="fade-up" data-aos-delay="100" class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 hover-lift transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-700 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Transparansi APBDes</h3>
                <p class="text-xs text-slate-500 mt-0.5">Laporan realisasi anggaran pendapatan & belanja desa.</p>
            </div>
        </a>

        {{-- Card 3: Pengaduan Warga NIK --}}
        <a href="{{ route('public.pengaduan.index') }}" data-aos="fade-up" data-aos-delay="200" class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-amber-300 hover-lift transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition-colors">Pengaduan Online NIK</h3>
                <p class="text-xs text-slate-500 mt-0.5">Laporan sarana publik & pantau status tiket aduan.</p>
            </div>
        </a>

        {{-- Card 4: UMKM & Potensi Desa --}}
        <a href="{{ route('public.umkm') }}" data-aos="fade-up" data-aos-delay="300" class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-indigo-300 hover-lift transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 group-hover:bg-indigo-700 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-700 transition-colors">Direktori UMKM</h3>
                <p class="text-xs text-slate-500 mt-0.5">Katalog produk warga & komoditas unggulan desa.</p>
            </div>
        </a>

    </div>
</section>


{{-- ── 3. SAMBUTAN KEPALA DESA & PROFIL ──────────────────────────────────── --}}
<section id="sambutan" class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            {{-- Sambutan Kepala Desa Card --}}
            <div data-aos="fade-right" data-aos-duration="700" class="lg:col-span-5 bg-slate-50 p-6 sm:p-8 rounded-2xl border border-slate-200 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black text-xl shadow-md border-2 border-white shrink-0">
                        <svg class="w-8 h-8 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 block">KATA PENGANTAR</span>
                        <h3 class="text-base font-extrabold text-slate-900">Kepala Desa</h3>
                        <p class="text-xs text-slate-500">{{ $settings['nama_desa'] ?? 'Desa Digital' }}</p>
                    </div>
                </div>

                <blockquote class="text-xs sm:text-sm text-slate-700 italic leading-relaxed border-l-4 border-blue-600 pl-4 py-1 font-serif-accent">
                    "Selamat datang di portal informasi dan pelayanan resmi Pemerintah {{ $settings['nama_desa'] ?? 'Desa Digital' }}. Website ini kami persembahkan sebagai sarana akuntabilitas, kemudahan pengurusan administrasi warga secara cepat dan transparan, serta media promosi potensi ekonomi masyarakat."
                </blockquote>

                <div class="pt-2 flex items-center justify-between text-xs text-slate-500 border-t border-slate-200">
                    <span class="font-semibold text-slate-800">Pemerintah {{ $settings['nama_desa'] ?? 'Desa' }}</span>
                    <a href="{{ route('public.profil') }}" class="font-bold text-blue-700 hover:underline">
                        Struktur Organisasi →
                    </a>
                </div>
            </div>

            {{-- Visi & Misi Desa --}}
            <div data-aos="fade-left" data-aos-duration="700" class="lg:col-span-7 space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700">ARAH KEBIJAKAN & PEMBANGUNAN</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Visi & Misi Pemerintah Desa</h2>
                </div>

                <div class="bg-blue-50/70 p-6 rounded-2xl border border-blue-100 space-y-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-700">VISI UTAMA</span>
                    <p class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                        "Terwujudnya Tata Kelola Pemerintahan Desa yang Bersih, Transparan, Mandiri, dan Berdaya Saing Melalui Pelayanan Prima Berbasis Teknologi dan Kearifan Lokal."
                    </p>
                </div>

                <div class="space-y-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">MISI PEMERINTAH DESA:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-700">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-1.5">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 text-xs font-black flex items-center justify-center">1</span>
                            <h4 class="font-bold text-slate-900">Pelayanan Cepat</h4>
                            <p class="text-slate-500 leading-relaxed">Optimalisasi administrasi warga bebas biaya dan transparan.</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-1.5">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 text-xs font-black flex items-center justify-center">2</span>
                            <h4 class="font-bold text-slate-900">Transparansi Anggaran</h4>
                            <p class="text-slate-500 leading-relaxed">Keterbukaan penggunaan APBDes sesuai regulasi pemerintah.</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-1.5">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 text-xs font-black flex items-center justify-center">3</span>
                            <h4 class="font-bold text-slate-900">Ekonomi Warga</h4>
                            <p class="text-slate-500 leading-relaxed">Pemberdayaan UMKM, BUMDes, dan komoditas unggulan desa.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ── 4. LAYANAN ADMINISTRASI KEPENDUDUKAN ──────────────────────────────── --}}
<section id="layanan" class="py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section Header --}}
        <div data-aos="fade-up" class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">PELAYANAN PUBLIK RESMI</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Layanan Administrasi Desa</h2>
            <p class="text-xs sm:text-sm text-slate-600">Pengurusan berkas kependudukan resmi secara cepat, bebas biaya, dan akuntabel.</p>
        </div>

        {{-- Service Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Layanan 1: Domisili --}}
            <div data-aos="zoom-in" data-aos-delay="0" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">BEBAS BIAYA</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Surat Keterangan Domisili</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Menerangkan tempat tinggal warga di wilayah desa untuk keperluan pekerjaan, pendidikan, atau perbankan.</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">Syarat: KK & KTP</span>
                    <a href="{{ route('public.layanan') }}" class="font-bold text-blue-700 hover:underline">Persyaratan & Alur →</a>
                </div>
            </div>

            {{-- Layanan 2: SKTM --}}
            <div data-aos="zoom-in" data-aos-delay="100" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">BEBAS BIAYA</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Surat Keterangan Tidak Mampu (SKTM)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pengantar resmi untuk beasiswa pendidikan siswa/mahasiswa atau jaminan keringanan biaya kesehatan RS.</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">Syarat: KK, KTP, Pengantar RT</span>
                    <a href="{{ route('public.layanan') }}" class="font-bold text-blue-700 hover:underline">Persyaratan & Alur →</a>
                </div>
            </div>

            {{-- Layanan 3: SKU --}}
            <div data-aos="zoom-in" data-aos-delay="200" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h2m0 0h2m-2-4h2m-2-4h2m-2-4h2"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">BEBAS BIAYA</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Surat Keterangan Usaha (SKU)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Legalisasi kepemilikan usaha mikro warga untuk pengajuan KUR perbankan atau izin usaha OSS.</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">Syarat: KTP, KK, Bukti Usaha</span>
                    <a href="{{ route('public.layanan') }}" class="font-bold text-blue-700 hover:underline">Persyaratan & Alur →</a>
                </div>
            </div>

            {{-- Layanan 4: Pengantar Kelahiran / Kematian --}}
            <div data-aos="zoom-in" data-aos-delay="0" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">BEBAS BIAYA</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengantar Kelahiran & Kematian</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Penerbitan surat pengantar desa untuk penerbitan Akta Kelahiran atau Akta Kematian di Disdukcapil.</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">Syarat: Surat Bidan / RT</span>
                    <a href="{{ route('public.layanan') }}" class="font-bold text-blue-700 hover:underline">Persyaratan & Alur →</a>
                </div>
            </div>

            {{-- Layanan 5: Pindah Tempat --}}
            <div data-aos="zoom-in" data-aos-delay="100" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">BEBAS BIAYA</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengantar Pindah Domisili</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pengurusan berkas kepindahan tempat tinggal antar desa, kecamatan, atau antar kabupaten/provinsi (SKPWNI).</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">Syarat: KTP & KK Asli</span>
                    <a href="{{ route('public.layanan') }}" class="font-bold text-blue-700 hover:underline">Persyaratan & Alur →</a>
                </div>
            </div>

            {{-- Layanan 6: Pengaduan Warga --}}
            <div data-aos="zoom-in" data-aos-delay="200" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-amber-50 text-amber-700 rounded-xl border border-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200">ONLINE 24 JAM</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengaduan Berbasis NIK</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Sarana penyampaian aspirasi dan laporan kerusakan infrastruktur yang diverifikasi langsung dengan database kependudukan.</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-medium text-slate-500">NIK Terdaftar</span>
                    <a href="{{ route('public.pengaduan.index') }}" class="font-bold text-blue-700 hover:underline">Kirim Laporan →</a>
                </div>
            </div>

        </div>

        <div class="text-center pt-2">
            <a href="{{ route('public.layanan') }}" class="inline-flex items-center gap-2 px-6 py-3 text-xs font-bold rounded-xl bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-blue-700 transition-colors shadow-xs">
                <span>Lihat Seluruh Katalog Layanan & Alur Berkas</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>


{{-- ── 5. DEMOGRAFI & DATA STATISTIK PENDUDUK ────────────────────────────── --}}
<section id="statistik" class="py-16 bg-white border-y border-slate-200" x-data="{ demoTab: 'gender' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section Header --}}
        <div data-aos="fade-up" class="text-center max-w-3xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">DATA KEPENDUDUKAN RESMI</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Demografi & Statistik Penduduk</h2>
            <p class="text-xs sm:text-sm text-slate-600">Statistik kependudukan terverifikasi yang bersumber dari sistem registrasi administrasi desa.</p>
        </div>

        {{-- 4 Metric Summary Cards --}}
        <div data-aos="fade-up" data-aos-delay="100" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center space-y-1 hover-lift transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Penduduk</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block">{{ number_format($demographics['total_penduduk'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-[11px] text-slate-500 block">L {{ $demographics['gender']['persen_L'] ?? 0 }}% | P {{ $demographics['gender']['persen_P'] ?? 0 }}%</span>
            </div>

            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center space-y-1 hover-lift transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Kartu Keluarga</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block">{{ number_format($stats['total_kk'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-[11px] text-slate-500 block">KK Terdaftar Resmi</span>
            </div>

            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center space-y-1 hover-lift transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Wilayah Administrasi</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-blue-700 block">{{ $stats['total_dusun'] ?? 0 }} Dusun</span>
                <span class="text-[11px] text-slate-500 block">{{ $stats['total_rw'] ?? 0 }} RW | {{ $stats['total_rt'] ?? 0 }} RT</span>
            </div>

            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center space-y-1 hover-lift transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Kategori Agama</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700 block">{{ count($demographics['agama'] ?? []) }} Agama</span>
                <span class="text-[11px] text-slate-500 block">Keharmonisan Warga</span>
            </div>
        </div>

        {{-- Interactive Tabs & Panels --}}
        <div data-aos="fade-up" data-aos-delay="150" class="bg-slate-50 p-6 sm:p-8 rounded-3xl border border-slate-200 space-y-6">
            
            {{-- Tabs Controls --}}
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-4">
                <button x-on:click="demoTab = 'gender'"
                        :class="demoTab === 'gender' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                    Jenis Kelamin
                </button>
                <button x-on:click="demoTab = 'dusun'"
                        :class="demoTab === 'dusun' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                    Sebaran Dusun
                </button>
                <button x-on:click="demoTab = 'pekerjaan'"
                        :class="demoTab === 'pekerjaan' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                    Mata Pencaharian
                </button>
                <button x-on:click="demoTab = 'pendidikan'"
                        :class="demoTab === 'pendidikan' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                    Tingkat Pendidikan
                </button>
                <button x-on:click="demoTab = 'agama'"
                        :class="demoTab === 'agama' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                    Agama & Kepercayaan
                </button>
            </div>

            {{-- Panel 1: JENIS KELAMIN --}}
            <div x-show="demoTab === 'gender'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 flex items-center justify-center">
                    <div class="w-full h-56">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-7 space-y-4">
                    <h3 class="text-base font-bold text-slate-900">Distribusi Jenis Kelamin</h3>
                    <p class="text-xs text-slate-500">Perbandingan jumlah penduduk laki-laki dan perempuan di wilayah {{ $settings['nama_desa'] ?? 'desa' }}.</p>
                    
                    <div class="space-y-3 pt-2">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-blue-700">Laki-laki</span>
                                <span class="text-slate-900">{{ number_format($demographics['gender']['L'] ?? 0, 0, ',', '.') }} Jiwa ({{ $demographics['gender']['persen_L'] ?? 0 }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-blue-700 h-2.5 rounded-full" style="width: {{ $demographics['gender']['persen_L'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-indigo-600">Perempuan</span>
                                <span class="text-slate-900">{{ number_format($demographics['gender']['P'] ?? 0, 0, ',', '.') }} Jiwa ({{ $demographics['gender']['persen_P'] ?? 0 }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $demographics['gender']['persen_P'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel 2: SEBARAN DUSUN --}}
            <div x-show="demoTab === 'dusun'" class="space-y-4" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Sebaran Penduduk Berdasarkan Dusun</h3>
                    <p class="text-xs text-slate-500">Grafik perbandingan sebaran jumlah jiwa di tiap wilayah dusun.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200">
                    <div class="h-64">
                        <canvas id="chartDusun"></canvas>
                    </div>
                </div>
            </div>

            {{-- Panel 3: MATA PENCAHARIAN --}}
            <div x-show="demoTab === 'pekerjaan'" class="space-y-4" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Mata Pencaharian & Sektor Pekerjaan Warga</h3>
                    <p class="text-xs text-slate-500">Distribusi profesi dan bidang pekerjaan penduduk usia produktif.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $totDemo = $demographics['total_penduduk'] ?? 1; @endphp
                    @forelse($demographics['pekerjaan'] as $pekerjaanName => $total)
                        @php $pct = $totDemo > 0 ? round(($total / $totDemo) * 100, 1) : 0; @endphp
                        <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-slate-900">{{ $pekerjaanName }}</span>
                                <span class="text-blue-700">{{ number_format($total, 0, ',', '.') }} Jiwa ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-700 h-2 rounded-full" style="width: {{ min(100, max(5, $pct * 2)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 text-slate-400 text-xs italic">Belum ada data pekerjaan.</div>
                    @endforelse
                </div>
            </div>

            {{-- Panel 4: TINGKAT PENDIDIKAN --}}
            <div x-show="demoTab === 'pendidikan'" class="space-y-4" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Jenjang Pendidikan Terakhir Warga</h3>
                    <p class="text-xs text-slate-500">Persentase tingkat kelulusan pendidikan formal penduduk.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $totDemo = $demographics['total_penduduk'] ?? 1; @endphp
                    @forelse($demographics['pendidikan'] as $pendidikanName => $total)
                        @php $pct = $totDemo > 0 ? round(($total / $totDemo) * 100, 1) : 0; @endphp
                        <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-slate-900">{{ $pendidikanName }}</span>
                                <span class="text-emerald-700">{{ number_format($total, 0, ',', '.') }} Jiwa ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ min(100, max(5, $pct * 2)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 text-slate-400 text-xs italic">Belum ada data pendidikan.</div>
                    @endforelse
                </div>
            </div>

            {{-- Panel 5: AGAMA --}}
            <div x-show="demoTab === 'agama'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center" style="display: none;">
                <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 flex items-center justify-center">
                    <div class="w-full h-56">
                        <canvas id="chartAgama"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-7 space-y-4">
                    <h3 class="text-base font-bold text-slate-900">Toleransi & Pemeluk Agama</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="text-slate-500 border-b border-slate-200 font-bold">
                                    <th class="py-2.5 px-2">Agama / Kepercayaan</th>
                                    <th class="py-2.5 px-2 text-right">Jumlah Pemeluk</th>
                                    <th class="py-2.5 px-2 text-center w-24">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php $totDemo = $demographics['total_penduduk'] ?? 1; @endphp
                                @forelse($demographics['agama'] as $agamaName => $total)
                                    @php $pct = $totDemo > 0 ? round(($total / $totDemo) * 100, 1) : 0; @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-2 font-bold text-slate-900">{{ $agamaName }}</td>
                                        <td class="py-2.5 px-2 text-right font-semibold text-slate-800">{{ number_format($total, 0, ',', '.') }} Jiwa</td>
                                        <td class="py-2.5 px-2 text-center">
                                            <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 font-bold rounded text-[11px] border border-emerald-200">{{ $pct }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-slate-400 italic">Belum ada data agama.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ── 6. TRANSPARANSI APBDES (PERMENDAGRI NO. 20/2018) ──────────────────── --}}
<section id="apbdes" class="py-16 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10" x-data="{ activeTab: 'belanja' }">
        
        {{-- Section Header --}}
        <div data-aos="fade-up" class="text-center max-w-3xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">KETERBUKAAN INFORMASI PUBLIK</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Transparansi APBDes Tahun {{ $apbdes['tahun'] }}</h2>
            <p class="text-xs sm:text-sm text-slate-600">Laporan Anggaran Pendapatan dan Belanja Desa Terbuka Berdasarkan Permendagri No. 20 Tahun 2018.</p>
        </div>

        {{-- 4 APBDes Metric Cards --}}
        <div data-aos="fade-up" data-aos-delay="100" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Pendapatan --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 hover-lift transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendapatan</span>
                    <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $apbdes['pendapatan_persen'] }}%</span>
                </div>
                <div class="text-2xl font-black text-emerald-700">Rp {{ number_format($apbdes['pendapatan_realisasi'], 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    Target Anggaran: <strong class="text-slate-800">Rp {{ number_format($apbdes['pendapatan_anggaran'], 0, ',', '.') }}</strong>
                </div>
            </div>

            {{-- Card 2: Belanja --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 hover-lift transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Belanja</span>
                    <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">{{ $apbdes['belanja_persen'] }}%</span>
                </div>
                <div class="text-2xl font-black text-rose-700">Rp {{ number_format($apbdes['belanja_realisasi'], 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    Pagu Belanja: <strong class="text-slate-800">Rp {{ number_format($apbdes['belanja_anggaran'], 0, ',', '.') }}</strong>
                </div>
            </div>

            {{-- Card 3: Pembiayaan --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 hover-lift transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pembiayaan (Netto)</span>
                    <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">{{ $apbdes['pembiayaan_persen'] }}%</span>
                </div>
                <div class="text-2xl font-black text-blue-700">Rp {{ number_format($apbdes['pembiayaan_realisasi'], 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    Pagu Pembiayaan: <strong class="text-slate-800">Rp {{ number_format($apbdes['pembiayaan_anggaran'], 0, ',', '.') }}</strong>
                </div>
            </div>

            {{-- Card 4: Surplus / Defisit --}}
            @php $surplus = $apbdes['surplus_defisit']; @endphp
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 hover-lift transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Surplus / (Defisit)</span>
                    <span class="text-xs font-extrabold px-2 py-0.5 rounded-md {{ $surplus >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $surplus >= 0 ? 'SURPLUS' : 'DEFISIT' }}
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900">Rp {{ number_format(abs($surplus), 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    {{ $surplus >= 0 ? 'Pendapatan melampaui realisasi belanja' : 'Realisasi belanja melampaui pendapatan' }}
                </div>
            </div>

        </div>

        {{-- Detailed Tabs Container --}}
        <div data-aos="fade-up" data-aos-delay="150" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
            
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div class="flex items-center gap-2">
                    <button x-on:click="activeTab = 'belanja'"
                            :class="activeTab === 'belanja' ? 'bg-blue-700 text-white font-bold' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                        Belanja Desa (5 Bidang)
                    </button>
                    <button x-on:click="activeTab = 'pendapatan'"
                            :class="activeTab === 'pendapatan' ? 'bg-blue-700 text-white font-bold' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                        Pendapatan Desa
                    </button>
                    <button x-on:click="activeTab = 'pembiayaan'"
                            :class="activeTab === 'pembiayaan' ? 'bg-blue-700 text-white font-bold' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs font-semibold rounded-xl transition-all">
                        Pembiayaan Desa
                    </button>
                </div>
                <span class="text-xs text-slate-500 font-medium">Tahun Anggaran: <strong class="text-slate-900">{{ $apbdes['tahun'] }}</strong></span>
            </div>

            {{-- Tab 1: BELANJA DESA (5 BIDANG) --}}
            <div x-show="activeTab === 'belanja'" class="space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rincian Belanja Menurut 5 Bidang Resmi Permendagri</h3>
                    <p class="text-xs text-slate-500">Klasifikasi resmi bidang penyelenggaraan pemerintahan, pembangunan, pembinaan, pemberdayaan, dan penanggulangan bencana.</p>
                </div>

                <div class="space-y-5">
                    @forelse($apbdes['grouped_belanja'] as $subName => $group)
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900">{{ $group['name'] }}</h4>
                                    <p class="text-[11px] text-slate-500">
                                        Pagu: <strong>Rp {{ number_format($group['anggaran'], 0, ',', '.') }}</strong> | 
                                        Realisasi: <strong class="text-emerald-700">Rp {{ number_format($group['realisasi'], 0, ',', '.') }}</strong> |
                                        Sisa: <strong class="text-rose-600">Rp {{ number_format($group['sisa'], 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-700 h-2 rounded-full" style="width: {{ $group['persen'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-black text-blue-700 min-w-[45px] text-right">{{ $group['persen'] }}%</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="text-slate-500 border-b border-slate-200 font-bold">
                                             <th class="py-2 px-2">Program / Kegiatan</th>
                                             <th class="py-2 px-2 text-right">Pagu Target</th>
                                             <th class="py-2 px-2 text-right">Realisasi</th>
                                             <th class="py-2 px-2 text-right">Sisa Anggaran</th>
                                             <th class="py-2 px-2 text-center w-20">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $item)
                                            @php $itemPersen = $item->jumlah > 0 ? min(100, round(($item->realisasi / $item->jumlah) * 100, 1)) : 0; @endphp
                                            <tr class="hover:bg-white transition-colors">
                                                <td class="py-2 px-2">
                                                    <span class="text-slate-900 font-bold block">{{ $item->kategori }}</span>
                                                    @if($item->keterangan)
                                                        <span class="text-[11px] text-slate-500 block">{{ $item->keterangan }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-2 text-right font-semibold text-slate-900">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-right font-bold text-emerald-700">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-right font-medium text-rose-600">Rp {{ number_format(max(0, $item->jumlah - $item->realisasi), 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-center">
                                                    <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded text-[10px] border border-blue-200">{{ $itemPersen }}%</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs italic">Belum ada rincian belanja tercatat.</div>
                    @endforelse
                </div>
            </div>

            {{-- Tab 2: PENDAPATAN DESA --}}
            <div x-show="activeTab === 'pendapatan'" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rincian Pendapatan Desa (PADes & Transfer)</h3>
                    <p class="text-xs text-slate-500">Pendapatan Asli Desa, Dana Desa APBN, Bagi Hasil Pajak/Retribusi, dan Alokasi Dana Desa (ADD).</p>
                </div>

                <div class="space-y-5">
                    @forelse($apbdes['grouped_pendapatan'] as $subName => $group)
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900">{{ $group['name'] }}</h4>
                                    <p class="text-[11px] text-slate-500">
                                        Target: <strong>Rp {{ number_format($group['anggaran'], 0, ',', '.') }}</strong> | 
                                        Realisasi: <strong class="text-emerald-700">Rp {{ number_format($group['realisasi'], 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                                <span class="text-xs font-black text-emerald-700">{{ $group['persen'] }}%</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="text-slate-500 border-b border-slate-200 font-bold">
                                            <th class="py-2 px-2">Sumber Pendapatan</th>
                                            <th class="py-2 px-2 text-right">Target Anggaran</th>
                                            <th class="py-2 px-2 text-right">Realisasi Diterima</th>
                                            <th class="py-2 px-2 text-center w-20">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $item)
                                            @php $itemPersen = $item->jumlah > 0 ? min(100, round(($item->realisasi / $item->jumlah) * 100, 1)) : 0; @endphp
                                            <tr class="hover:bg-white">
                                                <td class="py-2 px-2">
                                                    <span class="text-slate-900 font-bold block">{{ $item->kategori }}</span>
                                                    @if($item->keterangan)
                                                        <span class="text-[11px] text-slate-500 block">{{ $item->keterangan }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-2 text-right font-semibold text-slate-900">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-right font-bold text-emerald-700">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-center">
                                                    <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold rounded text-[10px] border border-emerald-200">{{ $itemPersen }}%</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs italic">Belum ada data pendapatan tercatat.</div>
                    @endforelse
                </div>
            </div>

            {{-- Tab 3: PEMBIAYAAN DESA --}}
            <div x-show="activeTab === 'pembiayaan'" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rincian Pembiayaan Desa</h3>
                    <p class="text-xs text-slate-500">Penerimaan pembiayaan (SiLPA) & Pengeluaran pembiayaan (Penyertaan modal BUMDes).</p>
                </div>

                <div class="space-y-5">
                    @forelse($apbdes['grouped_pembiayaan'] as $subName => $group)
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900">{{ $group['name'] }}</h4>
                                    <p class="text-[11px] text-slate-500">
                                        Pagu: <strong>Rp {{ number_format($group['anggaran'], 0, ',', '.') }}</strong> | 
                                        Realisasi: <strong class="text-blue-700">Rp {{ number_format($group['realisasi'], 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                                <span class="text-xs font-black text-blue-700">{{ $group['persen'] }}%</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="text-slate-500 border-b border-slate-200 font-bold">
                                            <th class="py-2 px-2">Uraian Pembiayaan</th>
                                            <th class="py-2 px-2 text-right">Pagu Pembiayaan</th>
                                            <th class="py-2 px-2 text-right">Realisasi</th>
                                            <th class="py-2 px-2 text-center w-20">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $item)
                                            @php $itemPersen = $item->jumlah > 0 ? min(100, round(($item->realisasi / $item->jumlah) * 100, 1)) : 0; @endphp
                                            <tr class="hover:bg-white">
                                                <td class="py-2 px-2">
                                                    <span class="text-slate-900 font-bold block">{{ $item->kategori }}</span>
                                                    @if($item->keterangan)
                                                        <span class="text-[11px] text-slate-500 block">{{ $item->keterangan }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-2 text-right font-semibold text-slate-900">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-right font-bold text-blue-700">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                                <td class="py-2 px-2 text-center">
                                                    <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded text-[10px] border border-blue-200">{{ $itemPersen }}%</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs italic">Belum ada data pembiayaan tercatat.</div>
                    @endforelse
                </div>
            </div>

            {{-- Grafik Perbandingan APBDes --}}
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Grafik Target vs Realisasi APBDes {{ $apbdes['tahun'] }}</h4>
                <div class="h-64">
                    <canvas id="chartApbdes"></canvas>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ── 7. KABAR & BERITA DESA TERKINI ───────────────────────────────────── --}}
<section id="berita" class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div data-aos="fade-up" class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">PUBLIKASI INFORMASI</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Kabar & Pengumuman Desa</h2>
                <p class="text-xs sm:text-sm text-slate-600">Berita resmi kegiatan kemasyarakatan, pembangunan, dan pengumuman pemerintahan desa.</p>
            </div>
            <a href="{{ route('public.berita.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-800">
                <span>Lihat Seluruh Berita</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($berita as $index => $b)
                <article data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 hover-lift transition-all flex flex-col justify-between">
                    <div>
                        <div class="h-48 bg-slate-100 overflow-hidden relative">
                            @if($b->cover_image)
                                <img src="{{ asset('storage/' . $b->cover_image) }}" alt="{{ $b->judul }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 text-xs font-semibold gap-1">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Dokumentasi Berita</span>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 bg-blue-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase shadow-xs">
                                {{ $b->kategori->nama ?? 'Warta Desa' }}
                            </span>
                        </div>
                        <div class="p-5 space-y-2.5">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ \Carbon\Carbon::parse($b->created_at)->translatedFormat('d F Y') }}</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 hover:text-blue-700 transition-colors">
                                <a href="{{ route('public.berita.show', $b->slug ?? $b->id) }}">{{ $b->judul }}</a>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($b->isi), 110) }}</p>
                        </div>
                    </div>
                    <div class="p-5 pt-0 border-t border-slate-100 mt-2">
                        <a href="{{ route('public.berita.show', $b->slug ?? $b->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:text-blue-900 pt-3">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="md:col-span-3 text-center py-12 text-slate-400 text-xs italic bg-slate-50 rounded-2xl border border-slate-200">
                    Belum ada kabar berita yang dipublikasikan.
                </div>
            @endforelse
        </div>

    </div>
</section>


{{-- ── 8. PETA LOKASI WILAYAH & KONTAK KANTOR ───────────────────────────── --}}
<section id="kontak" class="py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            {{-- Left: Office Information --}}
            <div data-aos="fade-right" class="lg:col-span-5 space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700">LOKASI & PELAYANAN</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kantor Pelayanan Desa</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Silakan kunjungi kantor desa pada jam pelayanan operasional atau hubungi kontak kami.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs hover-lift transition-all">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-bold text-slate-900 block mb-0.5">Alamat Kantor Desa</strong>
                            <span class="text-slate-600">{{ $settings['alamat_kantor'] ?? 'Jl. Raya Utama Desa No. 01, Kecamatan Digital, Kabupaten Smart' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs hover-lift transition-all">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0 border border-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-bold text-slate-900 block mb-0.5">Telepon & WhatsApp Layanan</strong>
                            <span class="text-slate-600">{{ $settings['telepon_desa'] ?? '+62 812-3456-7890' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs hover-lift transition-all">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-bold text-slate-900 block mb-0.5">Surat Elektronik (Email)</strong>
                            <span class="text-slate-600">{{ $settings['email_desa'] ?? 'kontak@desadigital.desa.id' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Interactive Leaflet Map --}}
            <div data-aos="fade-left" class="lg:col-span-7 bg-white p-3 rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div id="mapDesa" class="h-80 sm:h-96 rounded-2xl z-10"></div>
            </div>

        </div>

    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Chart Gender
    const ctxGender = document.getElementById('chartGender')?.getContext('2d');
    if (ctxGender) {
        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $demographics['gender']['L'] ?? 0 }}, {{ $demographics['gender']['P'] ?? 0 }}],
                    backgroundColor: ['#1D4ED8', '#4F46E5'],
                    borderWidth: 3,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' } }
                    } 
                }
            }
        });
    }

    // 2. Chart Dusun
    const ctxDusun = document.getElementById('chartDusun')?.getContext('2d');
    if (ctxDusun) {
        const dusunLabels = {!! json_encode(array_keys($demographics['dusun'] ?? [])) !!};
        const dusunData = {!! json_encode(array_values($demographics['dusun'] ?? [])) !!};
        new Chart(ctxDusun, {
            type: 'bar',
            data: {
                labels: dusunLabels.length ? dusunLabels : ['Dusun Krajan', 'Dusun Mulyoasri'],
                datasets: [{
                    label: 'Jumlah Jiwa',
                    data: dusunData.length ? dusunData : [120, 150],
                    backgroundColor: '#1D4ED8',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#F1F5F9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } } }
                }
            }
        });
    }

    // 3. Chart Agama
    const ctxAgama = document.getElementById('chartAgama')?.getContext('2d');
    if (ctxAgama) {
        const agamaLabels = {!! json_encode(array_keys($demographics['agama'] ?? [])) !!};
        const agamaData = {!! json_encode(array_values($demographics['agama'] ?? [])) !!};
        new Chart(ctxAgama, {
            type: 'doughnut',
            data: {
                labels: agamaLabels.length ? agamaLabels : ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'],
                datasets: [{
                    data: agamaData.length ? agamaData : [300, 20, 10, 5, 2],
                    backgroundColor: ['#059669', '#1D4ED8', '#D97706', '#7C3AED', '#DC2626'],
                    borderWidth: 3,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' } }
                    } 
                }
            }
        });
    }

    // 4. Chart APBDes
    const ctxApbdes = document.getElementById('chartApbdes')?.getContext('2d');
    if (ctxApbdes) {
        new Chart(ctxApbdes, {
            type: 'bar',
            data: {
                labels: ['Pendapatan Desa', 'Belanja Desa', 'Pembiayaan Netto'],
                datasets: [
                    {
                        label: 'Pagu Target (Rp)',
                        data: [{{ $apbdes['pendapatan_anggaran'] }}, {{ $apbdes['belanja_anggaran'] }}, {{ $apbdes['pembiayaan_anggaran'] }}],
                        backgroundColor: '#94A3B8',
                        borderRadius: 6
                    },
                    {
                        label: 'Realisasi Diterima/Terpakai (Rp)',
                        data: [{{ $apbdes['pendapatan_realisasi'] }}, {{ $apbdes['belanja_realisasi'] }}, {{ $apbdes['pembiayaan_realisasi'] }}],
                        backgroundColor: '#1D4ED8',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                    } 
                },
                scales: {
                    y: { grid: { color: '#F1F5F9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } } }
                }
            }
        });
    }

    // 5. Leaflet Map
    const mapContainer = document.getElementById('mapDesa');
    if (mapContainer && typeof L !== 'undefined') {
        const map = L.map('mapDesa').setView([-7.250445, 112.768845], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        L.marker([-7.250445, 112.768845]).addTo(map)
            .bindPopup('<b>Kantor Pemerintah Desa</b><br>{{ $settings["alamat_kantor"] ?? "Jl. Raya Utama Desa No. 01" }}')
            .openPopup();
    }

});
</script>
@endpush

@endsection
