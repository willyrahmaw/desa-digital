@extends('layouts.public')

@section('title', 'Profil Desa & Struktur Organisasi — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Sejarah, Visi Misi, Geografis, Wilayah Administratif, dan Struktur Organisasi Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . '.')

@section('content')

{{-- ── 1. BREADCRUMBS ──────────────────────────────────────────────────── --}}
<div class="bg-white border-b border-slate-200 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs font-semibold text-slate-500 flex items-center gap-2">
        <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">Profil Desa</span>
    </div>
</div>

{{-- ── 2. HERO IDENTITY & QUICK STATS ──────────────────────────────────── --}}
<section class="bg-white border-b border-slate-200 py-10 lg:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Identity Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4 sm:gap-6">
                @if(!empty($settings['logo_desa']))
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center p-3 shrink-0 shadow-xs">
                        <img src="{{ asset('storage/' . $settings['logo_desa']) }}" alt="Logo {{ $settings['nama_desa'] ?? 'Desa' }}" class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-700 shrink-0 shadow-xs">
                        <svg class="w-10 h-10 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 4 4.5.5-3.25 3.25.75 4.5-4-2.25-4 2.25.75-4.5-3.25-3.25 4.5-.5z" fill="currentColor"/>
                        </svg>
                    </div>
                @endif

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                            PROFIL PEMERINTAHAN DESA
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                            ● Status Aktif & Terverifikasi
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Pemerintah {{ $settings['nama_desa'] ?? 'Desa Candraloka' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium">
                        Kecamatan {{ $settings['nama_kecamatan'] ?? ($settings['kecamatan'] ?? 'Astraguna') }}, Kabupaten {{ $settings['nama_kabupaten'] ?? ($settings['kabupaten'] ?? 'Nirwana Raya') }}, Provinsi {{ $settings['provinsi'] ?? 'Fantasia Nusantara' }} (Kode Pos: {{ $settings['kode_pos'] ?? '99881' }})
                    </p>
                </div>
            </div>

            {{-- Motto Box (Bright) --}}
            <div class="bg-blue-50/60 p-5 rounded-2xl border border-blue-200/80 max-w-md lg:text-right shrink-0">
                <span class="text-[10px] font-extrabold text-blue-700 uppercase tracking-widest block">Motto Pembangunan</span>
                <p class="text-xs sm:text-sm font-extrabold text-slate-800 italic font-serif-accent mt-1">
                    "{{ $settings['motto_desa'] ?? 'Harmoni Alam, Cahaya Kemakmuran, dan Kearifan Bersama' }}"
                </p>
            </div>
        </div>

        {{-- Quick Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Penduduk</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($stats['total_penduduk'] ?? 0, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-500">Jiwa</span>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Kepala Keluarga</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($stats['total_kk'] ?? 0, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-500">KK</span>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Wilayah Dusun</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl sm:text-2xl font-black text-blue-700">{{ $stats['total_dusun'] ?? 0 }}</span>
                    <span class="text-xs font-semibold text-slate-500">Dusun</span>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Rukun Warga / RT</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl sm:text-2xl font-black text-emerald-700">{{ $stats['total_rw'] ?? 0 }} RW</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $stats['total_rt'] ?? 0 }} RT</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ── 3. MAIN PROFILE CONTENT & SECTIONS ──────────────────────────────── --}}
<section class="py-10 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section: Sambutan Kepala Desa --}}
        <div class="bg-white text-slate-900 p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0 shadow-2xs">
                <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="space-y-2 text-center md:text-left flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 text-[10px] font-bold uppercase tracking-wider">
                    <span>Sambutan Kepala Desa</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                    "Membangun Tata Kelola Desa yang Bersih, Akuntabel, dan Melayani"
                </h3>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-serif italic">
                    "Kami berkomitmen menghadirkan pelayanan publik yang transparan, tertib administrasi, serta mendorong kemandirian ekonomi desa melalui keterbukaan informasi dan pelayanan prima kepada seluruh warga masyarakat."
                </p>
                <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-center md:justify-start gap-3 text-xs">
                    <span class="font-bold text-slate-900">{{ $settings['nama_kades'] ?? 'Kepala Desa' }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-600 font-medium">Kepala {{ $settings['nama_desa'] ?? 'Desa Candraloka' }}</span>
                </div>
            </div>
        </div>

        {{-- Section: Visi, Misi & Nilai Dasar --}}
        <div class="space-y-6">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">Arah Kebijakan</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">Visi & Misi Pemerintahan Desa</h2>
                <p class="text-xs sm:text-sm text-slate-600">Landasan dan sasaran strategis penyelenggaraan pemerintahan dan pembangunan desa.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                
                {{-- Visi Card --}}
                <div class="lg:col-span-5 bg-white text-slate-900 p-6 sm:p-7 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between space-y-5">
                    <div class="space-y-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-700 block">Visi Desa</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-0.5">Cita-Cita Pembangunan</h3>
                        </div>
                        <blockquote class="text-xs sm:text-sm text-slate-800 leading-relaxed italic font-serif bg-slate-50 p-4 rounded-lg border border-slate-200">
                            "{{ $settings['visi'] ?? 'Terwujudnya Desa yang Maju, Mandiri, Religius, dan Berdaya Saing melalui Tata Kelola Pemerintahan yang Transparan dan Berbasis Digital.' }}"
                        </blockquote>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs text-slate-600 font-semibold">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Ditetapkan dalam Dokumen RPJMDes</span>
                    </div>
                </div>

                {{-- Misi Card --}}
                <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-xl border border-slate-200 shadow-xs space-y-4 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 block">Misi Pembangunan</span>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Agenda Kerja & Sasaran Pelayanan</h3>
                            </div>
                        </div>

                        <div class="space-y-2.5 pt-1">
                            @php
                                $misiRaw = $settings['misi'] ?? "1. Meningkatkan kualitas pelayanan publik dan transparansi tata kelola APBDes berbasis teknologi informasi.\n2. Mengoptimalkan pembangunan infrastruktur pedesaan yang merata dan ramah lingkungan.\n3. Mengembangkan potensi ekonomi lokal melalui pembinaan UMKM dan penguatan unit usaha BUMDes.\n4. Meningkatkan derajat kesehatan masyarakat melalui program posyandu terpadu dan pencegahan stunting.";
                                $misiLines = array_filter(explode("\n", str_replace("\r", "", $misiRaw)));
                            @endphp

                            @foreach($misiLines as $idx => $line)
                                @php
                                    $cleanLine = preg_replace('/^[0-9]+[\.\)]\s*/', '', trim($line));
                                @endphp
                                @if(!empty($cleanLine))
                                    <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 border border-slate-200/80">
                                        <span class="w-5 h-5 rounded bg-blue-700 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">
                                            {{ $cleanLine }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- 4 Pilar Tata Kelola --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                    <div class="text-[11px] font-bold text-blue-700 uppercase">Pilar 01</div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase">Transparansi</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Keterbukaan menyeluruh pengelolaan anggaran APBDes dan kegiatan pembangunan desa.</p>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                    <div class="text-[11px] font-bold text-emerald-700 uppercase">Pilar 02</div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase">Pelayanan Prima</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Penyelenggaraan administrasi kependudukan yang tertib, santun, dan bebas biaya pungutan.</p>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                    <div class="text-[11px] font-bold text-amber-700 uppercase">Pilar 03</div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase">Inovasi Digital</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Pemanfaatan sistem data kependudukan terintegrasi untuk mempercepat akses layanan.</p>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                    <div class="text-[11px] font-bold text-purple-700 uppercase">Pilar 04</div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase">Partisipasi Warga</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Mendorong musyawarah desa, sinergi BPD, RT/RW, dan lembaga kemasyarakatan.</p>
                </div>
            </div>
        </div>

        {{-- Section: Sejarah & Batas Wilayah Geografis --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            {{-- Sejarah Desa --}}
            <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-xl border border-slate-200 shadow-xs space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 block">Kilas Balik</span>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Sejarah Singkat Desa</h3>
                    </div>
                </div>
                <div class="prose max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed space-y-2.5 pt-1 font-normal">
                    <p>
                        <strong>{{ $settings['nama_desa'] ?? 'Desa Candraloka' }}</strong> memiliki rekam jejak panjang yang berakar dari kearifan lokal masyarakat agraris. Tumbuh dari pemukiman yang menjunjung tinggi musyawarah dan gotong royong, desa ini berkembang menjadi pusat komunitas yang dinamis dan berdaya saing.
                    </p>
                    <p>
                        Memasuki era transformasi digital, Pemerintah Desa mengintegrasikan teknologi informasi dalam tata kelola administrasi kependudukan dan transparansi APBDes. Modernisasi ini dirancang untuk mempercepat proses birokrasi, mencegah terjadinya hambatan pelayanan, serta memastikan seluruh hak dan bantuan sosial tersalurkan secara tepat sasaran.
                    </p>
                </div>
            </div>

            {{-- Batas Wilayah Geografis --}}
            <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-xl border border-slate-200 shadow-xs space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 block">Kondisi Geografis</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Batas Wilayah Administratif</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-xs pt-1">
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded bg-blue-100 text-blue-700 font-bold text-[10px] flex items-center justify-center">U</span>
                                <span class="font-bold text-slate-800">Sebelah Utara:</span>
                            </div>
                            <span class="text-slate-600 font-medium">Kecamatan Wilayah Utara</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded bg-emerald-100 text-emerald-700 font-bold text-[10px] flex items-center justify-center">S</span>
                                <span class="font-bold text-slate-800">Sebelah Selatan:</span>
                            </div>
                            <span class="text-slate-600 font-medium">Batas Sungai & Jalur Utama</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded bg-amber-100 text-amber-700 font-bold text-[10px] flex items-center justify-center">T</span>
                                <span class="font-bold text-slate-800">Sebelah Timur:</span>
                            </div>
                            <span class="text-slate-600 font-medium">Desa Tetangga Sektor Timur</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded bg-purple-100 text-purple-700 font-bold text-[10px] flex items-center justify-center">B</span>
                                <span class="font-bold text-slate-800">Sebelah Barat:</span>
                            </div>
                            <span class="text-slate-600 font-medium">Kawasan Pertanian & Perkebunan</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Section: Wilayah Administratif Kedusunan --}}
        @if(!empty($demographics['dusun_details']) && count($demographics['dusun_details']) > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">Pembagian Wilayah</span>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">Daftar Kedusunan & Rukun Warga</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($demographics['dusun_details'] as $dusun)
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    DUSUN
                                </span>
                                <span class="text-xs font-bold text-slate-900">{{ $dusun['persen'] ?? 0 }}% Warga</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $dusun['nama'] ?? 'Dusun' }}</h4>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">
                                    {{ number_format($dusun['total_penduduk'] ?? 0, 0, ',', '.') }} Jiwa Penduduk
                                </p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-600 font-medium">
                                <span>{{ $dusun['total_rw'] ?? 0 }} RW</span>
                                <span>•</span>
                                <span>{{ $dusun['total_rt'] ?? 0 }} RT</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── 4. STRUKTUR ORGANISASI PERANGKAT DESA ────────────────────────── --}}
        <div class="space-y-6 pt-2">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">Aparatur Pemerintah</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">Struktur Organisasi Perangkat Desa</h2>
                <p class="text-xs sm:text-sm text-slate-600">Jajaran pejabat dan aparatur yang bertugas melayani masyarakat {{ $settings['nama_desa'] ?? 'Desa Digital' }}.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @forelse($perangkat as $p)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs flex flex-col justify-between text-center p-5 space-y-3">
                        
                        <div class="space-y-3">
                            {{-- Photo Container --}}
                            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-lg bg-slate-100 overflow-hidden border border-slate-200 flex items-center justify-center shadow-2xs">
                                @if($p->foto)
                                    <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Identity --}}
                            <div class="space-y-1">
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $p->jabatan->nama ?? 'Perangkat Desa' }}
                                </span>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug pt-0.5">
                                    {{ $p->nama }}
                                </h4>
                            </div>
                        </div>

                        {{-- NIP / Status footer --}}
                        <div class="pt-2.5 border-t border-slate-100 text-[11px] text-slate-500 font-mono">
                            @if(!empty($p->nip))
                                <span>NIP. {{ $p->nip }}</span>
                            @else
                                <span class="text-slate-400 font-sans">Aparatur Desa</span>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-slate-400 text-xs italic bg-white rounded-xl border border-slate-200">
                        Belum ada data perangkat desa yang dimasukkan ke dalam sistem.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── 5. CONTACT & OFFICE INFO CARD ────────────────────────────────── --}}
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">Kantor Pelayanan</span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">Lokasi & Kontak Kantor Desa</h3>
                    <p class="text-xs text-slate-600 mt-0.5">Buka setiap hari kerja untuk pelayanan administrasi surat dan kependudukan.</p>
                </div>
                <a href="{{ route('public.layanan') }}" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold transition-all shadow-2xs shrink-0">
                    <span>Lihat Persyaratan Surat →</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-700">
                <div class="flex items-start gap-3 p-3.5 rounded-lg bg-slate-50 border border-slate-200/80">
                    <div class="w-7 h-7 rounded bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">Alamat Kantor</span>
                        <p class="text-slate-600 leading-relaxed">{{ $settings['alamat_kantor'] ?? 'Jl. Raya Utama Desa No. 01, Kecamatan Digital' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3.5 rounded-lg bg-slate-50 border border-slate-200/80">
                    <div class="w-7 h-7 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">Jam Pelayanan</span>
                        <p class="text-slate-600 leading-relaxed">Senin – Jumat : 08:00 – 15:00 WIB<br><span class="text-slate-400">Sabtu & Minggu : Libur</span></p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3.5 rounded-lg bg-slate-50 border border-slate-200/80">
                    <div class="w-7 h-7 rounded bg-slate-200 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">Kontak Resmi</span>
                        <p class="text-slate-600 leading-relaxed">{{ $settings['telepon_desa'] ?? ($settings['telp_desa'] ?? '+62 341-1234567') }}<br>{{ $settings['email_desa'] ?? 'kontak@desa.id' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

