@extends('layouts.public')

@section('title', 'Galeri Foto & Video Dokumentasi — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Dokumentasi foto dan video resmi kegiatan, pembangunan fisik, dan kebudayaan Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

{{-- ── 1. PAGE HEADER ──────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Galeri Dokumentasi</span>
        </div>
        <div class="pt-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">DOKUMENTASI RESMI</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Galeri Foto & Kegiatan Desa</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Koleksi dokumentasi visual kegiatan pembangunan, kemasyarakatan, dan seni budaya desa.</p>
        </div>
    </div>
</section>

{{-- ── 2. GALLERY GRID WITH LIGHTBOX ───────────────────────────────────── --}}
<section x-data="{ lightboxOpen: false, currentImg: '', currentCaption: '' }" class="py-14 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($galeriList as $g)
                <div x-on:click="lightboxOpen = true; currentImg = '{{ asset('storage/' . $g->file_path) }}'; currentCaption = '{{ addslashes($g->judul ?? $g->keterangan ?? 'Dokumentasi Desa') }}'" 
                     class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs cursor-pointer group hover:border-blue-400 hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="h-52 bg-slate-100 overflow-hidden relative">
                        @if($g->file_path)
                            <img src="{{ asset('storage/' . $g->file_path) }}" alt="{{ $g->judul ?? 'Dokumentasi' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-semibold bg-slate-100">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs gap-1.5 backdrop-blur-[2px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            <span>Perbesar Foto</span>
                        </div>
                    </div>
                    <div class="p-4 bg-white">
                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $g->judul ?? $g->keterangan ?? 'Dokumentasi Kegiatan Desa' }}</h3>
                        <span class="text-[10px] text-slate-500 block mt-1">Dokumentasi Resmi</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-400 text-xs italic bg-white rounded-2xl border border-slate-200">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Belum ada dokumentasi galeri yang diunggah.</span>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ── 3. ALPINE LIGHTBOX MODAL ────────────────────────────────────── --}}
    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div x-on:click.outside="lightboxOpen = false" 
             class="bg-white rounded-3xl p-5 max-w-4xl w-full space-y-3 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 truncate pr-4" x-text="currentCaption"></h4>
                <button x-on:click="lightboxOpen = false" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-colors">
                    ✕ Tutup
                </button>
            </div>
            <div class="max-h-[75vh] overflow-hidden rounded-2xl bg-black flex items-center justify-center">
                <img :src="currentImg" class="max-h-[75vh] w-auto object-contain">
            </div>
        </div>
    </div>
</section>

@endsection
