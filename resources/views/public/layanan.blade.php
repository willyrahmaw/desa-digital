@extends('layouts.public')

@section('title', 'Katalog Layanan Publik & Persyaratan Surat — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Katalog lengkap layanan surat kependudukan, izin usaha, pengantar bansos, persyaratan berkas, dan alur pengurusan resmi di Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

<div x-data="{
    searchQuery: '',
    selectedCategory: 'all',
    copiedService: null,
    toastOpen: false,
    toastMessage: '',
    copyRequirements(title, requirements) {
        const text = `📋 Persyaratan ${title} (${'{{ $settings['nama_desa'] ?? 'Pemerintah Desa' }}'}):\n\n` + 
                     requirements.map((r, i) => `${i + 1}. ${r}`).join('\n') + 
                     `\n\n📌 Biaya: Rp 0 (Gratis)\n⏰ Jam Layanan: Senin - Jumat (08.00 - 15.00 WIB)\n🌐 Info lengkap: ${window.location.href}`;
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                this.showCopyToast(title);
            }).catch(() => {
                this.fallbackCopy(text, title);
            });
        } else {
            this.fallbackCopy(text, title);
        }
    },
    fallbackCopy(text, title) {
        const temp = document.createElement('textarea');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        try {
            document.execCommand('copy');
            this.showCopyToast(title);
        } catch (e) {
            console.error('Copy failed', e);
        }
        document.body.removeChild(temp);
    },
    showCopyToast(title) {
        this.copiedService = title;
        this.toastMessage = `Daftar persyaratan ${title} berhasil disalin ke clipboard!`;
        this.toastOpen = true;
        setTimeout(() => { this.copiedService = null; }, 3000);
        setTimeout(() => { this.toastOpen = false; }, 4000);
    },
    matchesFilter(title, desc, category, requirements) {
        const q = this.searchQuery.toLowerCase().trim();
        const matchesCat = (this.selectedCategory === 'all' || category.toLowerCase() === this.selectedCategory.toLowerCase());
        if (!q) return matchesCat;
        
        const inTitle = title.toLowerCase().includes(q);
        const inDesc = desc.toLowerCase().includes(q);
        const inReq = requirements.some(r => r.toLowerCase().includes(q));
        const inCat = category.toLowerCase().includes(q);
        
        return matchesCat && (inTitle || inDesc || inReq || inCat);
    }
}" class="relative">

    {{-- ── 1. BREADCRUMBS ──────────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-slate-200 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs font-semibold text-slate-500 flex items-center gap-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Katalog Layanan</span>
        </div>
    </div>

    {{-- ── 2. HERO BANNER & HIGHLIGHT BADGES ───────────────────────────────── --}}
    <section class="bg-white border-b border-slate-200 py-10 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="max-w-3xl space-y-2">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 inline-block">
                    PELAYANAN PUBLIK & ADMINISTRASI WARGA
                </span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Katalog Layanan & Persyaratan Surat
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Standar Operasional Prosedur (SOP), kelengkapan dokumen persyaratan, serta panduan alur pengurusan administrasi kependudukan di Pemerintah {{ $settings['nama_desa'] ?? 'Desa Digital' }}.
                </p>
            </div>

            {{-- 3 Key Guarantees Badges (Bright Theme) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-blue-50/60 border border-blue-200 text-xs">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Jam Pelayanan Loket</span>
                        <span class="font-extrabold text-slate-900">Senin – Jumat (08.00 – 15.00 WIB)</span>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-xs">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Biaya Pengurusan</span>
                        <span class="font-extrabold text-emerald-900">Rp 0 / 100% Bebas Biaya (Gratis)</span>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-800 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Lokasi Pelayanan</span>
                        <span class="font-extrabold text-slate-900">Balai Desa {{ $settings['nama_desa'] ?? '' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── 3. SEARCH & INTERACTIVE CATEGORY FILTER ─────────────────────────── --}}
    <section class="py-8 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Search & Filters Controls Bar --}}
            <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                
                <div class="flex flex-col md:flex-row items-center gap-3">
                    {{-- Realtime Search Input --}}
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama surat atau kata kunci (contoh: Domisili, SKU, SKTM, Kelahiran, Pindah)..."
                               class="w-full rounded-2xl border border-slate-300 pl-10 pr-10 py-3 text-xs sm:text-sm font-medium focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none bg-slate-50 focus:bg-white transition-all">
                        <button x-show="searchQuery" @click="searchQuery = ''" type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Search Stats Counter --}}
                    <div class="hidden lg:flex items-center gap-2 text-xs font-bold text-slate-600 shrink-0 px-3 py-2 bg-slate-100 rounded-xl">
                        <span>Total: {{ count($layananList) }} Layanan Tersedia</span>
                    </div>
                </div>

                {{-- Category Filter Pills --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 scrollbar-none text-xs">
                    <button @click="selectedCategory = 'all'" type="button"
                            :class="selectedCategory === 'all' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-4 py-2 rounded-xl transition-all shrink-0">
                        Semua Layanan ({{ count($layananList) }})
                    </button>

                    <button @click="selectedCategory = 'Kependudukan'" type="button"
                            :class="selectedCategory === 'Kependudukan' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-4 py-2 rounded-xl transition-all shrink-0">
                        Kependudukan
                    </button>

                    <button @click="selectedCategory = 'Sosial & Pendidikan'" type="button"
                            :class="selectedCategory === 'Sosial & Pendidikan' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-4 py-2 rounded-xl transition-all shrink-0">
                        Sosial & Pendidikan
                    </button>

                    <button @click="selectedCategory = 'Ekonomi & UMKM'" type="button"
                            :class="selectedCategory === 'Ekonomi & UMKM' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-4 py-2 rounded-xl transition-all shrink-0">
                        Ekonomi & UMKM
                    </button>

                    <button @click="selectedCategory = 'Layanan Online'" type="button"
                            :class="selectedCategory === 'Layanan Online' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-4 py-2 rounded-xl transition-all shrink-0">
                        Layanan Online
                    </button>
                </div>

            </div>

            {{-- ── 4. SERVICES GRID (2 COLUMNS) ────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($layananList as $index => $l)
                <div x-show="matchesFilter('{{ addslashes($l['title']) }}', '{{ addslashes($l['desc']) }}', '{{ addslashes($l['category']) }}', {{ json_encode($l['syarat']) }})"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xs hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between group">

                    <div>
                        {{-- Card Header --}}
                        <div class="p-6 sm:p-7 border-b border-slate-100 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200">
                                        {{ $l['category'] }}
                                    </span>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                        Rp 0 / GRATIS
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $l['jam'] }}</span>
                                </span>
                            </div>

                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 leading-snug group-hover:text-blue-700 transition-colors">
                                    {{ $l['title'] }}
                                </h3>
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    {{ $l['desc'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Card Content: Persyaratan & Alur (2-Column Subgrid) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-slate-50/50">
                            
                            {{-- Persyaratan Berkas --}}
                            <div class="p-6 space-y-3">
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <span>Persyaratan Berkas:</span>
                                </span>
                                <ul class="space-y-2">
                                    @foreach($l['syarat'] as $s)
                                    <li class="flex items-start gap-2 text-xs text-slate-700">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span class="font-medium leading-snug">{{ $s }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Alur Pengajuan --}}
                            <div class="p-6 space-y-3">
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span>Alur Pelayanan:</span>
                                </span>
                                <ol class="space-y-2.5">
                                    @foreach($l['alur'] as $step => $a)
                                    <li class="flex items-center gap-2 text-xs text-slate-700">
                                        <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-800 text-[10px] font-black flex items-center justify-center shrink-0">
                                            {{ $step + 1 }}
                                        </span>
                                        <span class="font-medium leading-snug">{{ $a }}</span>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>

                        </div>
                    </div>

                    {{-- Card Footer Actions --}}
                    <div class="p-4 sm:p-5 bg-white border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        <button @click="copyRequirements('{{ addslashes($l['title']) }}', {{ json_encode($l['syarat']) }})"
                                type="button"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span x-text="copiedService === '{{ addslashes($l['title']) }}' ? 'Syarat Disalin ✓' : 'Salin Daftar Syarat'">Salin Daftar Syarat</span>
                        </button>

                        @if($l['category'] === 'Layanan Online')
                            <a href="{{ route('public.pengaduan.index') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition-all shadow-xs">
                                <span>Buka Form Pengaduan Online →</span>
                            </a>
                        @else
                            <a href="https://api.whatsapp.com/send?text={{ urlencode('Halo Admin Desa, saya ingin konsultasi mengenai pengurusan ' . $l['title']) }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Tanya Petugas Loket</span>
                            </a>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

            {{-- ── 5. ALUR UMUM PROSEDUR LAYANAN (VISUAL STEPPER) ───────────────── --}}
            <div class="bg-white p-7 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-8 mt-6">
                <div class="text-center max-w-xl mx-auto space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">STANDAR PROSEDUR</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-2">4 Langkah Alur Pengurusan Surat Desa</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Alur sederhana dan cepat tanpa perantara, langsung dilayani di loket kantor desa.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    {{-- Step 1 --}}
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-3 text-center sm:text-left relative">
                        <span class="w-9 h-9 rounded-xl bg-blue-700 text-white font-extrabold text-xs flex items-center justify-center mx-auto sm:mx-0 shadow-xs">01</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Siapkan Berkas</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">Lengkapi fotokopi KTP, KK, dan Surat Pengantar dari RT/RW setempat sesuai kebutuhan surat.</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-3 text-center sm:text-left relative">
                        <span class="w-9 h-9 rounded-xl bg-blue-700 text-white font-extrabold text-xs flex items-center justify-center mx-auto sm:mx-0 shadow-xs">02</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Datang ke Balai Desa</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">Kunjungi loket pelayanan terpadu kantor desa pada jam kerja (Senin - Jumat, 08.00 - 15.00 WIB).</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-3 text-center sm:text-left relative">
                        <span class="w-9 h-9 rounded-xl bg-blue-700 text-white font-extrabold text-xs flex items-center justify-center mx-auto sm:mx-0 shadow-xs">03</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Verifikasi & Cetak</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">Petugas memverifikasi kelengkapan berkas, memproses draf surat, dan meminta tanda tangan Kepala Desa.</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-3 text-center sm:text-left relative">
                        <span class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold text-xs flex items-center justify-center mx-auto sm:mx-0 shadow-xs">04</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Surat Resmi Terbit</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">Surat resmi berstempel basah/elektronik siap dibawa pulang tanpa dipungut biaya apapun (Gratis).</p>
                    </div>
                </div>
            </div>

            {{-- ── 6. FAQ ACCORDION PANDUAN PELAYANAN ───────────────────────────── --}}
            <div x-data="{ openFaq: null }" class="bg-white p-7 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">TANYA JAWAB</span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-2">Pertanyaan Umum Seputar Layanan Surat</h3>
                    <p class="text-xs sm:text-sm text-slate-600">Jawaban cepat atas pertanyaan yang sering diajukan warga saat mengurus dokumen.</p>
                </div>

                <div class="divide-y divide-slate-100 space-y-2">
                    {{-- FAQ 1 --}}
                    <div class="pt-3">
                        <button @click="openFaq = openFaq === 1 ? null : 1" type="button" class="w-full py-3 flex items-center justify-between text-left gap-4">
                            <span class="text-xs sm:text-sm font-bold text-slate-900">Berapa lama estimasi waktu proses pembuatan surat di kantor desa?</span>
                            <span :class="openFaq === 1 ? 'rotate-180 text-blue-700' : 'text-slate-400'" class="transition-transform shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div x-show="openFaq === 1" x-collapse class="pb-3 text-xs text-slate-600 leading-relaxed font-normal">
                            Untuk sebagian besar surat keterangan standar (Domisili, SKU, Keterangan Belum Menikah, dll.), proses pembuatan di loket kantor desa hanya membutuhkan waktu <strong>10 hingga 15 menit</strong> apabila seluruh berkas persyaratan telah lengkap dan pejabat yang berwenang berada di tempat.
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="pt-3">
                        <button @click="openFaq = openFaq === 2 ? null : 2" type="button" class="w-full py-3 flex items-center justify-between text-left gap-4">
                            <span class="text-xs sm:text-sm font-bold text-slate-900">Apakah ada biaya administrasi untuk pengurusan surat?</span>
                            <span :class="openFaq === 2 ? 'rotate-180 text-blue-700' : 'text-slate-400'" class="transition-transform shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div x-show="openFaq === 2" x-collapse class="pb-3 text-xs text-slate-600 leading-relaxed font-normal">
                            <strong>Tidak ada biaya sama sekali (Rp 0 / 100% Gratis).</strong> Seluruh pelayanan administrasi kependudukan dan persuratan di Pemerintah Desa bebas dari pungutan liar (pungli). Jika ada oknum yang meminta biaya, silakan laporkan melalui menu Pengaduan Warga.
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="pt-3">
                        <button @click="openFaq = openFaq === 3 ? null : 3" type="button" class="w-full py-3 flex items-center justify-between text-left gap-4">
                            <span class="text-xs sm:text-sm font-bold text-slate-900">Apakah pengurusan surat bisa diwakilkan oleh anggota keluarga?</span>
                            <span :class="openFaq === 3 ? 'rotate-180 text-blue-700' : 'text-slate-400'" class="transition-transform shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div x-show="openFaq === 3" x-collapse class="pb-3 text-xs text-slate-600 leading-relaxed font-normal">
                            Pengurusan dapat diwakilkan oleh anggota keluarga yang terdaftar dalam satu Kartu Keluarga (KK) dengan membawa KTP asli pemohon dan KTP asli perwakilan. Untuk surat tertentu seperti Surat Pengantar Pindah atau Pernyataan Ahli Waris, kehadiran pemohon langsung sangat dianjurkan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 7. HELPDESK & ADUAN WARGA CARD ──────────────────────────────── --}}
            <div class="bg-gradient-to-br from-blue-50/90 via-sky-50/40 to-white border border-blue-200 rounded-3xl p-7 sm:p-9 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xs">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full inline-block border border-blue-300">
                        PUSAT BANTUAN
                    </span>
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900">
                        Perlu Bantuan atau Mengalami Kendala Berkas Persyaratan?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl font-normal">
                        Petugas loket kantor desa siap melayani pertanyaan Anda pada jam kerja operasional, atau sampaikan aspirasi dan kendala melalui saluran pengaduan online resmi.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
                    <a href="{{ route('public.pengaduan.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 text-xs font-bold rounded-xl bg-blue-700 text-white hover:bg-blue-800 transition-all shadow-xs w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <span>Buat Pengaduan / Tanya Layanan</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- ── 8. TOAST NOTIFICATION ───────────────────────────────────────────── --}}
    <div x-show="toastOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4 scale-95"
         class="fixed bottom-6 right-6 z-50 max-w-sm w-full bg-white rounded-2xl shadow-2xl border border-emerald-200 p-4 flex items-start gap-3.5 pointer-events-auto"
         style="display: none;">
        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <h5 class="text-xs font-extrabold text-slate-900">Persyaratan Disalin!</h5>
            <p class="text-[11px] text-slate-600 mt-0.5" x-text="toastMessage"></p>
        </div>
        <button @click="toastOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

</div>

@endsection

