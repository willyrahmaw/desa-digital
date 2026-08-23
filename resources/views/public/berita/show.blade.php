@extends('layouts.public')

@section('title', $berita->judul . ' — Kabar ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', Str::limit(strip_tags($berita->ringkasan ?? $berita->isi), 150))
@if($berita->cover_image)
    @section('og_image', asset('storage/' . $berita->cover_image))
@endif

@section('content')

<div x-data="{
    copied: false,
    toastOpen: false,
    toastMessage: 'Tautan artikel telah tersimpan di clipboard dan siap dibagikan.',
    modalOpen: false,
    shareUrl: window.location.href,
    init() {
        this.shareUrl = window.location.href;
    },
    copyToClipboard() {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(this.shareUrl).then(() => {
                this.handleCopySuccess();
            }).catch(() => {
                this.fallbackCopy();
            });
        } else {
            this.fallbackCopy();
        }
    },
    fallbackCopy() {
        const tempInput = document.createElement('input');
        tempInput.value = this.shareUrl;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            this.handleCopySuccess();
        } catch (e) {
            console.error('Copy failed', e);
        }
        document.body.removeChild(tempInput);
    },
    handleCopySuccess() {
        this.copied = true;
        this.toastOpen = true;
        setTimeout(() => { this.copied = false; }, 3000);
        setTimeout(() => { this.toastOpen = false; }, 4000);
    }
}" class="relative">

    {{-- ── 1. BREADCRUMBS ──────────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-slate-200 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs font-semibold text-slate-500 flex items-center gap-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <a href="{{ route('public.berita.index') }}" class="hover:text-blue-700">Kabar Desa</a>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate max-w-xs sm:max-w-md">{{ $berita->judul }}</span>
        </div>
    </div>

    {{-- ── 2. ARTICLE MAIN SECTION & SIDEBAR ─────────────────────────────────── --}}
    <article class="py-10 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Left Column: Main Article (8 Cols) --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6">
                        
                        {{-- Metadata Row --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase border border-blue-200">
                                {{ $berita->kategori->nama ?? 'Warta Desa' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Redaksi Pemerintah Desa</span>
                            </span>
                        </div>

                        {{-- Article Title --}}
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
                            {{ $berita->judul }}
                        </h1>

                        {{-- Featured Image --}}
                        @if($berita->cover_image)
                            <div class="rounded-2xl overflow-hidden border border-slate-200 max-h-[480px] shadow-xs">
                                <img src="{{ asset('storage/' . $berita->cover_image) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        {{-- Article Body Content --}}
                        <div class="prose max-w-none text-sm sm:text-base text-slate-800 leading-relaxed space-y-4 pt-4 border-t border-slate-100 font-normal">
                            @if(strip_tags($berita->isi) !== $berita->isi)
                                {!! $berita->isi !!}
                            @else
                                {!! nl2br(e($berita->isi)) !!}
                            @endif
                        </div>

                        {{-- Share Actions (Full Suite) --}}
                        <div class="pt-6 border-t border-slate-100 space-y-3.5">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-3.5 bg-blue-700 rounded-xs inline-block"></span>
                                    <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Bagikan Informasi Ini:</span>
                                </div>
                                <button @click="modalOpen = true" type="button" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    <span>Lihat Semua Opsi Berbagi</span>
                                </button>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                {{-- WhatsApp --}}
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#25D366] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm transform active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                    <span>WhatsApp</span>
                                </a>

                                {{-- Facebook --}}
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1877F2] hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm transform active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    <span>Facebook</span>
                                </a>

                                {{-- Twitter / X --}}
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm transform active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    <span>X (Twitter)</span>
                                </a>

                                {{-- Telegram --}}
                                <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#229ED9] hover:bg-sky-600 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm transform active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.943z"/></svg>
                                    <span>Telegram</span>
                                </a>

                                {{-- Email --}}
                                <a href="mailto:?subject={{ urlencode($berita->judul) }}&body={{ urlencode('Baca berita lengkap di: ' . url()->current()) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-700 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm transform active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Email</span>
                                </a>

                                {{-- Salin Tautan (Copy Link with Toast Trigger) --}}
                                <button @click="copyToClipboard()" type="button"
                                        :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border-slate-200'"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all border shadow-xs transform active:scale-95">
                                    <template x-if="!copied">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            <span>Salin Tautan</span>
                                        </div>
                                    </template>
                                    <template x-if="copied">
                                        <div class="flex items-center gap-1.5 text-emerald-700">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Tautan Tersalin!</span>
                                        </div>
                                    </template>
                                </button>
                            </div>
                        </div>

                        {{-- Bottom Link Back --}}
                        <div class="pt-4 border-t border-slate-100">
                            <a href="{{ route('public.berita.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-blue-700 hover:text-blue-900 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Kembali ke Semua Kabar Desa</span>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Right Column: Sidebar (4 Cols) --}}
                <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    
                    {{-- Search Widget --}}
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-3">
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Cari Kabar Desa</span>
                        </h4>
                        <form action="{{ route('public.berita.index') }}" method="GET" class="relative">
                            <input type="text" name="q" placeholder="Cari kabar atau kata kunci..." 
                                   class="w-full rounded-xl border border-slate-300 pl-3.5 pr-10 py-2.5 text-xs font-medium focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none bg-slate-50 focus:bg-white transition-all">
                            <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>

                    {{-- Kabar Terkait Widget --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-5">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-4 bg-blue-700 rounded-xs"></span>
                                <h3 class="text-sm font-extrabold text-slate-900">Kabar Terkait</h3>
                            </div>
                            <a href="{{ route('public.berita.index') }}" class="text-[11px] font-bold text-blue-700 hover:underline">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($related as $r)
                                <article class="group pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                                    <a href="{{ route('public.berita.show', $r->slug ?? $r->id) }}" class="flex gap-3.5 items-start">
                                        @if($r->cover_image)
                                            <div class="w-20 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-100">
                                                <img src="{{ asset('storage/' . $r->cover_image) }}" alt="{{ $r->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                            </div>
                                        @else
                                            <div class="w-20 h-16 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0013.5 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14z"/></svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0 space-y-1">
                                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-medium">
                                                <span class="text-blue-700 font-semibold uppercase">{{ $r->kategori->nama ?? 'Warta' }}</span>
                                                <span>•</span>
                                                <span>{{ \Carbon\Carbon::parse($r->published_at ?? $r->created_at)->translatedFormat('d M Y') }}</span>
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug">
                                                {{ $r->judul }}
                                            </h4>
                                        </div>
                                    </a>
                                </article>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2">Belum ada kabar terkait lainnya.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Banner Layanan / Aduan (Bright Theme) --}}
                    <div class="bg-gradient-to-br from-blue-50/90 via-sky-50/40 to-white rounded-3xl p-6 text-slate-900 shadow-xs space-y-3.5 relative overflow-hidden border border-blue-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full inline-block border border-blue-300">
                            LAYANAN WARGA
                        </span>
                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                            Punya Aspirasi atau Kendala di Lingkungan Anda?
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Sampaikan laporan resmi ke Pemerintah Desa dengan cepat dan transparan melalui layanan aduan berbasis NIK.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('public.pengaduan.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                                <span>Buat Laporan Pengaduan →</span>
                            </a>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </article>

    {{-- ── 3. TOAST NOTIFICATION ───────────────────────────────────────────── --}}
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
            <h5 class="text-xs font-extrabold text-slate-900">Tautan Berhasil Disalin!</h5>
            <p class="text-[11px] text-slate-600 mt-0.5" x-text="toastMessage"></p>
        </div>
        <button @click="toastOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- ── 4. SHARE MODAL ─────────────────────────────────────────────────── --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="modalOpen = false"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5">
            
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900">Bagikan Kabar Desa</h3>
                </div>
                <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Article Preview Badge --}}
            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1">
                <span class="text-[10px] font-bold uppercase text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 inline-block">
                    {{ $berita->kategori->nama ?? 'Warta Desa' }}
                </span>
                <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug">{{ $berita->judul }}</h4>
            </div>

            {{-- Direct Copy Field --}}
            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block">Tautan Langsung</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly :value="shareUrl" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 font-mono select-all focus:outline-none focus:border-blue-500">
                    <button @click="copyToClipboard()" type="button" class="shrink-0 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1">
                        <template x-if="!copied">
                            <span>Salin</span>
                        </template>
                        <template x-if="copied">
                            <span>Tersalin ✓</span>
                        </template>
                    </button>
                </div>
            </div>

            {{-- Grid of Social Platforms --}}
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block">Kanal Media Sosial</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}" target="_blank"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition-all text-center gap-1.5 group">
                        <svg class="w-5 h-5 text-emerald-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span class="text-[11px] font-bold">WhatsApp</span>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 transition-all text-center gap-1.5 group">
                        <svg class="w-5 h-5 text-blue-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span class="text-[11px] font-bold">Facebook</span>
                    </a>

                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-900 border border-slate-200 transition-all text-center gap-1.5 group">
                        <svg class="w-5 h-5 text-slate-900 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        <span class="text-[11px] font-bold">X (Twitter)</span>
                    </a>

                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}" target="_blank"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 transition-all text-center gap-1.5 group">
                        <svg class="w-5 h-5 text-sky-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.943z"/></svg>
                        <span class="text-[11px] font-bold">Telegram</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

