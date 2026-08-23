@extends('layouts.public')

@section('title', 'Agenda & Jadwal Kegiatan Desa — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Jadwal dan agenda resmi musyawarah perencanaan, posyandu terpadu, gotong royong, dan kegiatan kemasyarakatan Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

<div x-data="{
    searchQuery: '',
    selectedCategory: 'all',
    timeFilter: 'all',
    copiedAgenda: null,
    toastOpen: false,
    toastMessage: '',
    nowIso: new Date().toISOString(),
    
    matchesFilter(judul, deskripsi, lokasi, kategori, startDateIso) {
        const q = this.searchQuery.toLowerCase().trim();
        const matchesCat = (this.selectedCategory === 'all' || kategori.toLowerCase() === this.selectedCategory.toLowerCase());
        
        // Time filter logic
        let matchesTime = true;
        if (this.timeFilter === 'upcoming') {
            matchesTime = (startDateIso >= this.nowIso.substring(0, 10));
        } else if (this.timeFilter === 'past') {
            matchesTime = (startDateIso < this.nowIso.substring(0, 10));
        }
        
        if (!q) return matchesCat && matchesTime;
        
        const inJudul = judul.toLowerCase().includes(q);
        const inDesc = deskripsi.toLowerCase().includes(q);
        const inLokasi = lokasi.toLowerCase().includes(q);
        const inKat = kategori.toLowerCase().includes(q);
        
        return matchesCat && matchesTime && (inJudul || inDesc || inLokasi || inKat);
    },
    
    getGoogleCalendarUrl(title, details, location, startFormatted, endFormatted) {
        const baseUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
        const textParam = '&text=' + encodeURIComponent(title);
        const detailsParam = '&details=' + encodeURIComponent(details + '\n\nDiselenggarakan oleh Pemerintah Desa.');
        const locationParam = '&location=' + encodeURIComponent(location);
        const datesParam = '&dates=' + startFormatted + '/' + endFormatted;
        return baseUrl + textParam + detailsParam + locationParam + datesParam;
    },
    
    copyAgendaDetails(title, dateStr, timeStr, location, desc) {
        const text = `📅 *Agenda Desa: ${title}*\n\n` +
                     `🗓️ *Hari/Tanggal:* ${dateStr}\n` +
                     `⏰ *Waktu:* ${timeStr}\n` +
                     `📍 *Lokasi:* ${location}\n` +
                     `📝 *Keterangan:* ${desc}\n\n` +
                     `🌐 *Info lengkap:* ${window.location.href}`;
                     
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
        this.copiedAgenda = title;
        this.toastMessage = `Rincian agenda '${title}' berhasil disalin ke clipboard!`;
        this.toastOpen = true;
        setTimeout(() => { this.copiedAgenda = null; }, 3000);
        setTimeout(() => { this.toastOpen = false; }, 4000);
    }
}" class="relative">

    {{-- ── 1. BREADCRUMBS ──────────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-slate-200 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs font-semibold text-slate-500 flex items-center gap-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Agenda Desa</span>
        </div>
    </div>

    {{-- ── 2. HERO HEADER & QUICK COUNTERS ─────────────────────────────────── --}}
    <section class="bg-white border-b border-slate-200 py-10 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="max-w-3xl space-y-2">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 inline-block">
                    JADWAL & KALENDER KEGIATAN
                </span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Agenda & Musyawarah Desa
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Jadwal resmi musyawarah perencanaan pembangunan, posyandu terpadu, kerja bakti lingkungan, dan kegiatan kemasyarakatan Pemerintah {{ $settings['nama_desa'] ?? 'Desa Digital' }}.
                </p>
            </div>

            {{-- KPI Summary Badges (Bright Theme) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                <div class="bg-blue-50/60 p-4 sm:p-5 rounded-2xl border border-blue-200 shadow-2xs space-y-1">
                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">Total Agenda</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900">{{ count($agenda) }}</span>
                        <span class="text-xs font-semibold text-slate-500">Kegiatan</span>
                    </div>
                </div>

                <div class="bg-emerald-50/60 p-4 sm:p-5 rounded-2xl border border-emerald-200 shadow-2xs space-y-1">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Musyawarah & Pleno</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900">{{ $agenda->filter(fn($a) => in_array(strtolower($a->kategori ?? ''), ['musyawarah', 'pemerintahan', 'rapat']))->count() }}</span>
                        <span class="text-xs font-semibold text-slate-500">Acara</span>
                    </div>
                </div>

                <div class="bg-purple-50/60 p-4 sm:p-5 rounded-2xl border border-purple-200 shadow-2xs space-y-1">
                    <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Kesehatan & Posyandu</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900">{{ $agenda->filter(fn($a) => strtolower($a->kategori ?? '') === 'kesehatan')->count() }}</span>
                        <span class="text-xs font-semibold text-slate-500">Layanan</span>
                    </div>
                </div>

                <div class="bg-amber-50/60 p-4 sm:p-5 rounded-2xl border border-amber-200 shadow-2xs space-y-1">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Sosial & Gotong Royong</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900">{{ $agenda->filter(fn($a) => in_array(strtolower($a->kategori ?? ''), ['gotong royong', 'kemasyarakatan', 'pelatihan']))->count() }}</span>
                        <span class="text-xs font-semibold text-slate-500">Kegiatan</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── 3. MAIN CONTENT: AGENDA STREAM & SIDEBAR ────────────────────────── --}}
    <section class="py-10 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT COLUMN: SEARCH, FILTERS & AGENDA LIST (8 cols) --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- Search & Category Filter Box --}}
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                        
                        {{-- Search Input Bar --}}
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Cari nama agenda, lokasi, atau kata kunci (contoh: RKPDes, Posyandu, Irigasi)..."
                                   class="w-full rounded-2xl border border-slate-300 pl-10 pr-10 py-3 text-xs sm:text-sm font-medium focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none bg-slate-50 focus:bg-white transition-all">
                            <button x-show="searchQuery" @click="searchQuery = ''" type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Filter Tabs (Category Pills) --}}
                        @php
                            $uniqueCategories = $agenda->pluck('kategori')->filter()->unique()->values();
                        @endphp
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none text-xs">
                            <button @click="selectedCategory = 'all'" type="button"
                                    :class="selectedCategory === 'all' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                                    class="px-3.5 py-1.5 rounded-xl transition-all shrink-0">
                                Semua ({{ count($agenda) }})
                            </button>

                            @foreach($uniqueCategories as $cat)
                                <button @click="selectedCategory = '{{ $cat }}'" type="button"
                                        :class="selectedCategory === '{{ $cat }}' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                                        class="px-3.5 py-1.5 rounded-xl transition-all shrink-0">
                                    {{ $cat }}
                                </button>
                            @endforeach
                        </div>

                    </div>

                    {{-- Agenda Cards Stream --}}
                    <div class="space-y-4">
                        @forelse($agenda as $a)
                            @php
                                $tMulai = \Carbon\Carbon::parse($a->tanggal_mulai);
                                $tSelesai = $a->tanggal_selesai ? \Carbon\Carbon::parse($a->tanggal_selesai) : $tMulai->copy()->addHours(2);
                                $gStart = $tMulai->format('Ymd\THis\Z');
                                $gEnd = $tSelesai->format('Ymd\THis\Z');
                                
                                $cat = $a->kategori ?? 'Pemerintahan';
                                $catLower = strtolower($cat);
                                
                                // Color badges mapping
                                if (in_array($catLower, ['musyawarah', 'pemerintahan', 'rapat'])) {
                                    $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                } elseif ($catLower === 'kesehatan') {
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                } elseif ($catLower === 'gotong royong') {
                                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                } elseif ($catLower === 'pelatihan') {
                                    $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                } else {
                                    $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
                                }
                            @endphp

                            <div x-show="matchesFilter('{{ addslashes($a->judul) }}', '{{ addslashes($a->deskripsi ?? '') }}', '{{ addslashes($a->lokasi ?? '') }}', '{{ addslashes($cat) }}', '{{ $tMulai->format('Y-m-d') }}')"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs hover:shadow-md hover:border-blue-300 transition-all flex flex-col sm:flex-row items-start gap-5 sm:gap-6 group">
                                
                                {{-- Calendar Date Block (Bright & High Contrast) --}}
                                <div class="w-full sm:w-20 rounded-2xl bg-blue-50 border border-blue-200 p-3 text-center shrink-0 shadow-2xs group-hover:bg-blue-600 group-hover:border-blue-600 transition-colors">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 group-hover:text-blue-100 block">
                                        {{ $tMulai->locale('id')->translatedFormat('M') }}
                                    </span>
                                    <span class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-white block leading-none my-0.5">
                                        {{ $tMulai->format('d') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-blue-200 block">
                                        {{ $tMulai->format('Y') }}
                                    </span>
                                </div>

                                {{-- Main Details --}}
                                <div class="flex-1 min-w-0 space-y-3">
                                    
                                    {{-- Badges Row --}}
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $badgeClass }}">
                                                {{ $cat }}
                                            </span>
                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Terjadwal Resmi</span>
                                            </span>
                                        </div>

                                        <span class="text-xs font-semibold text-slate-500">
                                            {{ $tMulai->locale('id')->translatedFormat('l, d F Y') }}
                                        </span>
                                    </div>

                                    {{-- Title & Description --}}
                                    <div>
                                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 leading-snug group-hover:text-blue-700 transition-colors">
                                            {{ $a->judul }}
                                        </h3>
                                        @if(!empty($a->deskripsi))
                                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-1.5 font-normal">
                                                {{ $a->deskripsi }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Meta Location & Time Strip --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span class="font-medium truncate"><strong>Lokasi:</strong> {{ $a->lokasi ?? 'Kantor Balai Desa' }}</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="font-medium"><strong>Waktu:</strong> {{ $tMulai->format('H:i') }} @if($a->tanggal_selesai) – {{ $tSelesai->format('H:i') }} @endif WIB</span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons (Google Calendar, Copy, WhatsApp) --}}
                                    <div class="pt-3 flex flex-wrap items-center gap-2.5">
                                        {{-- Add to Google Calendar --}}
                                        <a href="{{ 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($a->judul) . '&details=' . urlencode(($a->deskripsi ?? '') . "\n\nPemerintah Desa " . ($settings['nama_desa'] ?? '')) . '&location=' . urlencode($a->lokasi ?? 'Balai Desa') . '&dates=' . $gStart . '/' . $gEnd }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Simpan ke Kalender</span>
                                        </a>

                                        {{-- Copy Info --}}
                                        <button @click="copyAgendaDetails('{{ addslashes($a->judul) }}', '{{ $tMulai->locale('id')->translatedFormat('l, d F Y') }}', '{{ $tMulai->format('H:i') }} WIB', '{{ addslashes($a->lokasi ?? 'Kantor Desa') }}', '{{ addslashes($a->deskripsi ?? '') }}')"
                                                type="button"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            <span x-text="copiedAgenda === '{{ addslashes($a->judul) }}' ? 'Tersalin ✓' : 'Salin Info'">Salin Info</span>
                                        </button>

                                        {{-- Share to WhatsApp --}}
                                        <a href="https://api.whatsapp.com/send?text={{ urlencode("📅 *Agenda Desa: " . $a->judul . "*\n🗓️ Tanggal: " . $tMulai->locale('id')->translatedFormat('l, d F Y') . "\n⏰ Waktu: " . $tMulai->format('H:i') . " WIB\n📍 Lokasi: " . ($a->lokasi ?? 'Kantor Desa') . "\n\nInfo lengkap: " . url()->current()) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors ml-auto">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                            <span>Bagikan WA</span>
                                        </a>
                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="text-center py-16 text-slate-400 text-xs italic bg-white rounded-3xl border border-slate-200">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Belum ada agenda kegiatan mendatang yang dijadwalkan.</span>
                            </div>
                        @endforelse
                    </div>

                </div>

                {{-- RIGHT COLUMN: SIDEBAR WIDGETS (4 cols) --}}
                <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    
                    {{-- Participation in Musdes Card --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 block">PARTISIPASI WARGA</span>
                                <h4 class="text-sm font-extrabold text-slate-900">Musyawarah Terbuka</h4>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Setiap musyawarah perencanaan desa (Musdes/RKPDes) diselenggarakan secara terbuka dengan melibatkan delegasi RT/RW, Badan Permusyawaratan Desa (BPD), tokoh pemuda, dan kelompok keterwakilan perempuan.
                        </p>
                        <div class="p-3 bg-blue-50/60 rounded-2xl border border-blue-100 text-[11px] text-blue-900 flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Keputusan musyawarah menjadi dasar alokasi APBDes resmi.</span>
                        </div>
                    </div>

                    {{-- Usulan Agenda & Peminjaman Fasilitas Card --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 block">FASILITAS UMUM</span>
                                <h4 class="text-sm font-extrabold text-slate-900">Pinjam Balai & Lapangan</h4>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Warga atau kelompok kemasyarakatan yang hendak menggunakan balai pertemuan, gedung kesenian, atau lapangan olahraga desa dapat mengajukan surat permohonan izin ke loket kantor desa.
                        </p>
                        <a href="{{ route('public.layanan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition-colors">
                            <span>Lihat Ketentuan Layanan Persuratan →</span>
                        </a>
                    </div>

                    {{-- Banner Aduan & Bantuan Layanan (Bright) --}}
                    <div class="bg-gradient-to-br from-blue-50/90 via-sky-50/40 to-white rounded-3xl p-6 text-slate-900 shadow-xs space-y-3.5 relative overflow-hidden border border-blue-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full inline-block border border-blue-300">
                            ASPIRASI WARGA
                        </span>
                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                            Punya Usulan Kegiatan untuk Kemajuan Desa?
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Sampaikan masukan ide program kemasyarakatan atau laporkan kendala fasilitas umum melalui saluran pengaduan online resmi.
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('public.pengaduan.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                                <span>Kirim Usulan / Aduan Online →</span>
                            </a>
                        </div>
                    </div>

                </aside>

            </div>

        </div>
    </section>

    {{-- ── 4. TOAST NOTIFICATION ───────────────────────────────────────────── --}}
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
            <h5 class="text-xs font-extrabold text-slate-900">Agenda Disalin!</h5>
            <p class="text-[11px] text-slate-600 mt-0.5" x-text="toastMessage"></p>
        </div>
        <button @click="toastOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

</div>

@endsection

