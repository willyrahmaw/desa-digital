@extends('layouts.public')

@section('title', 'Direktori UMKM & Potensi Ekonomi — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Katalog usaha mikro, kecil, dan menengah (UMKM) warga serta komoditas potensi unggulan perekonomian Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

{{-- ── 1. PAGE HEADER ──────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Direktori UMKM & Potensi</span>
        </div>
        <div class="pt-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">PEMBERDAYAAN EKONOMI</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Direktori UMKM & Potensi Ekonomi Desa</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Katalog promosi produk lokal unggulan warga, komoditas desa, dan usaha mikro binaan BUMDes.</p>
        </div>
    </div>
</section>

{{-- ── 2. SEKTOR POTENSI EKONOMI UNGGULAN ──────────────────────────────── --}}
<section class="py-12 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="space-y-1">
            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-700">KOMODITAS UTAMA</span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Sektor Potensi Unggulan Wilayah</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Pertanian & Pangan Organik</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Lahan persawahan produktif penghasil beras dan sayuran organik berkualitas tinggi.</p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h2m0 0h2m-2-4h2m-2-4h2m-2-4h2"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Peternakan Terpadu</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Budidaya ternak kambing dan sapi potong mandiri kelompok tani peternak.</p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Perikanan Air Tawar</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Budidaya kolam ikan lele, nila, dan gurame air tawar binaan kelompok perikanan.</p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Kerajinan & Industri Kreatif</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Kerajinan anyaman bambu, batik khas desa, dan produk olahan makanan rumahan.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── 3. UMKM DIRECTORY GRID ─────────────────────────────────────────── --}}
<section class="py-14 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Katalog Produk & Usaha Warga</h2>
            <span class="text-xs text-slate-500 font-medium">Mendukung Gerakan Beli Produk Lokal Desa</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($umkmList as $u)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        @if(!empty($u->foto))
                            <div class="h-48 w-full bg-slate-100 overflow-hidden relative">
                                <img src="{{ asset('storage/' . $u->foto) }}" alt="{{ $u->nama_produk ?? $u->nama }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-3 left-3 text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md shadow-xs border border-slate-200">
                                    {{ $u->kategori->nama ?? 'Produk Unggulan' }}
                                </span>
                            </div>
                        @endif
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                @if(empty($u->foto))
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200">
                                        {{ $u->kategori->nama ?? 'Kuliner & Pangan' }}
                                    </span>
                                @endif
                                <span class="text-xs font-semibold text-slate-500 flex items-center gap-1 ml-auto">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ $u->pelaku->nama_pemilik ?? $u->pelaku->nama ?? 'Warga Desa' }}</span>
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $u->nama_produk ?? $u->nama }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">{{ $u->deskripsi }}</p>
                            @if($u->harga)
                                <div class="text-sm font-extrabold text-emerald-700 font-mono pt-1">
                                    Rp {{ number_format($u->harga, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 mt-2 space-y-2.5 text-xs">
                        <p class="text-slate-600 pt-3"><strong>Unit Usaha:</strong> {{ $u->pelaku->nama_usaha ?? $u->pelaku->nama ?? 'UMKM Mandiri Desa' }}</p>
                        @php
                            $waContact = $u->whatsapp ?: ($u->pelaku->no_hp ?? null);
                        @endphp
                        @if($waContact)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waContact) }}?text={{ urlencode('Halo, saya tertarik dengan produk ' . ($u->nama_produk ?? $u->nama) . ' yang terdaftar di Portal Desa.') }}" target="_blank" 
                               class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Hubungi Penjual via WhatsApp</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-400 text-xs italic bg-white rounded-2xl border border-slate-200">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Belum ada data produk UMKM yang terdaftar di sistem.</span>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
