@extends('layouts.public')

@section('title', 'Kabar & Pengumuman Desa Terkini — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Portal berita resmi, publikasi kegiatan, dan informasi pembangunan Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

{{-- ── 1. PAGE HEADER ──────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Kabar Desa</span>
        </div>
        <div class="pt-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">PORTAL INFORMASI</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Kabar & Pengumuman Desa</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Informasi resmi seputar program pembangunan, musyawarah warga, dan kegiatan kemasyarakatan.</p>
        </div>
    </div>
</section>

{{-- ── 2. SEARCH & ARTICLES GRID ───────────────────────────────────────── --}}
<section class="py-12 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Search & Filter Bar --}}
        <form action="{{ route('public.berita.index') }}" method="GET" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative flex-grow w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari judul berita, topik, atau kata kunci..." 
                       class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-xs font-medium focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-700 text-white font-bold text-xs rounded-xl hover:bg-blue-800 transition-colors shadow-xs shrink-0 flex items-center justify-center gap-2">
                <span>Cari Berita</span>
            </button>
        </form>

        {{-- Berita Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($beritaList as $b)
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between">
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
                <div class="col-span-full text-center py-16 text-slate-400 text-xs italic bg-white rounded-2xl border border-slate-200">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0013.5 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14z"/></svg>
                    <span>Tidak ada kabar berita yang sesuai dengan pencarian.</span>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($beritaList->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $beritaList->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
