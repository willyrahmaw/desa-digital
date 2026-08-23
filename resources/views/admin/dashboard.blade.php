@extends('layouts.admin')

@section('title', 'Dashboard Administrasi Desa')
@section('breadcrumb-item', 'Dashboard')
@section('page-title', 'Pusat Kendali Administrasi Desa')

@php
    $namaDesa = \App\Models\Pengaturan::where('key', 'nama_desa')->value('value') ?? 'Desa Krajan Mulyo';
    $bulanIni = \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y');
    $totalPengaduan = $stats['total_pengaduan'];
    $selesaiPct = $totalPengaduan > 0 ? round(($stats['pengaduan_selesai'] / $totalPengaduan) * 100) : 0;
@endphp

@section('content')
<div class="space-y-5">

    {{-- ── 1. EXECUTIVE OPERATIONAL HEADER ──────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-lg p-4 sm:p-5 shadow-2xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            {{-- Left: Identity & Live Context --}}
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Pelayanan Aktif
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium font-mono text-[11px]">T.A. {{ date('Y') }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    Pusat Informasi & Pelayanan Administrasi Desa
                </h1>
                <p class="text-xs text-slate-500">
                    Sistem Manajemen Satu Data Terpadu — Pemerintahan {{ $namaDesa }}
                </p>
            </div>

            {{-- Right: Fast Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.master.surat.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Buat Surat</span>
                </a>
                <a href="{{ route('admin.master.penduduk.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Tambah Penduduk</span>
                </a>
            </div>

        </div>
    </div>

    {{-- ── 2. OPERATIONAL ALERT (IF PENDING ITEMS EXIST) ────────── --}}
    @if($stats['surat_pending_count'] > 0 || $stats['pengaduan_pending'] > 0)
        <div class="p-3.5 rounded-lg bg-amber-50/80 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5 text-amber-900">
                <div class="p-1 rounded bg-amber-100 text-amber-800 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <span class="font-bold">Perhatian Petugas:</span>
                    <span class="text-amber-800">
                        Terdapat <strong>{{ $stats['surat_pending_count'] }} permohonan surat</strong> dan <strong>{{ $stats['pengaduan_pending'] }} pengaduan warga</strong> menunggu verifikasi.
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if($stats['surat_pending_count'] > 0)
                    <a href="{{ route('admin.master.surat.index') }}" class="px-2.5 py-1 rounded bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs transition-colors">
                        Verifikasi Surat &rarr;
                    </a>
                @endif
                @if($stats['pengaduan_pending'] > 0)
                    <a href="{{ route('admin.master.pengaduan.index') }}" class="px-2.5 py-1 rounded bg-white hover:bg-amber-100 border border-amber-300 text-amber-800 font-semibold text-xs transition-colors">
                        Tinjau Aduan &rarr;
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ── 3. EXECUTIVE KPI CARDS (HIGH DENSITY METRICS) ────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Stat 1: Kependudukan --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Kependudukan</span>
                    <div class="p-1.5 rounded bg-slate-100 text-slate-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-slate-900 font-mono">{{ number_format($stats['total_penduduk']) }}</span>
                    <span class="text-xs text-slate-500 font-medium">Jiwa</span>
                </div>
            </div>
            <div class="pt-2.5 mt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>L: <strong class="text-slate-800 font-mono">{{ number_format($stats['total_laki']) }}</strong> | P: <strong class="text-slate-800 font-mono">{{ number_format($stats['total_perempuan']) }}</strong></span>
                <span>KK: <strong class="text-slate-800 font-mono">{{ number_format($stats['total_kk']) }}</strong></span>
            </div>
        </div>

        {{-- Stat 2: Pelayanan Surat --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Surat Terbit (Bulan Ini)</span>
                    <div class="p-1.5 rounded bg-indigo-50 text-indigo-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-slate-900 font-mono">{{ number_format($stats['total_surat_bulan_ini']) }}</span>
                    <span class="text-xs text-slate-500 font-medium">Dokumen</span>
                </div>
            </div>
            <div class="pt-2.5 mt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Hari ini: <strong class="text-slate-800 font-mono">{{ $stats['total_surat_hari_ini'] }}</strong></span>
                <span class="font-semibold {{ $stats['surat_growth'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $stats['surat_growth'] >= 0 ? '+' : '' }}{{ $stats['surat_growth'] }}% MoM
                </span>
            </div>
        </div>

        {{-- Stat 3: Antrean Verifikasi --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Antrean Verifikasi</span>
                    <div class="p-1.5 rounded bg-amber-50 text-amber-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-amber-700 font-mono">{{ number_format($stats['surat_pending_count']) }}</span>
                    <span class="text-xs text-slate-500 font-medium">Permohonan</span>
                </div>
            </div>
            <div class="pt-2.5 mt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Disetujui: <strong class="text-slate-800 font-mono">{{ number_format($stats['surat_approved_count']) }}</strong></span>
                <a href="{{ route('admin.master.surat.index') }}" class="text-indigo-600 font-semibold hover:underline">Kelola &rarr;</a>
            </div>
        </div>

        {{-- Stat 4: Pengaduan Warga --}}
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pengaduan & Aspirasi</span>
                    <div class="p-1.5 rounded bg-blue-50 text-blue-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-slate-900 font-mono">{{ number_format($stats['total_pengaduan']) }}</span>
                    <span class="text-xs text-slate-500 font-medium">Laporan</span>
                </div>
            </div>
            <div class="pt-2.5 mt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Selesai: <strong class="text-emerald-700 font-mono">{{ $stats['pengaduan_selesai'] }} ({{ $selesaiPct }}%)</strong></span>
                <a href="{{ route('admin.master.pengaduan.index') }}" class="text-indigo-600 font-semibold hover:underline">Tinjau &rarr;</a>
            </div>
        </div>

    </div>

    {{-- ── 4. MAIN OPERATIONAL GRID (8 COLS / 4 COLS) ──────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- LEFT COLUMN: Tren Pelayanan, Antrean Verifikasi, Top Surat (8 Cols) --}}
        <div class="lg:col-span-8 space-y-5">
            
            {{-- Chart: Tren Pelayanan Surat 12 Bulan --}}
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 mb-4 gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Tren Penerbitan Surat Pelayanan</h2>
                        <p class="text-[11px] text-slate-500">Grafik volume dokumen administrasi per bulan di Tahun Anggaran {{ date('Y') }}</p>
                    </div>
                    <span class="text-[11px] font-mono font-semibold bg-slate-100 text-slate-600 px-2.5 py-1 rounded border border-slate-200 self-start sm:self-auto">
                        Total TA {{ date('Y') }}: {{ number_format($stats['total_surat_tahun_ini']) }} berkas
                    </span>
                </div>
                <div style="height: 220px; position: relative;">
                    <canvas id="suratTrendChart"></canvas>
                </div>
            </div>

            {{-- Table: Permohonan Surat Masuk Terbaru --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Permohonan Surat Terkini</h2>
                        <p class="text-[11px] text-slate-500">Daftar berkas administrasi kependudukan yang masuk ke sistem</p>
                    </div>
                    <a href="{{ route('admin.master.surat.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                                <th class="py-2.5 px-4">No. Surat / Registrasi</th>
                                <th class="py-2.5 px-4">Pemohon</th>
                                <th class="py-2.5 px-4">Jenis Surat</th>
                                <th class="py-2.5 px-4 text-center">Waktu Masuk</th>
                                <th class="py-2.5 px-4 text-center">Status</th>
                                <th class="py-2.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($stats['recent_surat'] as $surat)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 font-mono font-semibold text-slate-900 whitespace-nowrap">
                                        {{ $surat->nomor_surat ?: 'REG-' . str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                        {{ $surat->penduduk_nama }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        {{ $surat->jenis_surat_nama ?: 'Surat Keterangan' }}
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if($surat->status === 'approved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Disetujui</span>
                                        @elseif($surat->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">Pending</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20">{{ ucfirst($surat->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap space-x-1">
                                        @if($surat->status === 'approved')
                                            <a href="{{ route('admin.master.surat.print', $surat->id) }}" target="_blank" 
                                               class="inline-flex items-center px-2.5 py-1 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-[11px] transition-colors shadow-2xs">
                                                Cetak
                                            </a>
                                        @else
                                            <a href="{{ route('admin.master.surat.index') }}" 
                                               class="inline-flex items-center px-2.5 py-1 rounded bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-[11px] transition-colors shadow-2xs">
                                                Proses
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada rekaman surat yang diajukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card: Demografi Mata Pencaharian --}}
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Distribusi Profesi / Mata Pencaharian</h2>
                        <p class="text-[11px] text-slate-500">10 kelompok profesi terbesar penduduk desa</p>
                    </div>
                    <span class="text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">Top 10 Sektor</span>
                </div>
                <div style="height: 220px; position: relative;">
                    <canvas id="occupationChart"></canvas>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Agenda, Pengaduan, Dusun, Ringkasan Ekonomi (4 Cols) --}}
        <div class="lg:col-span-4 space-y-5">
            
            {{-- Widget: Agenda Resmi Hari Ini --}}
            <div class="bg-white border border-slate-200 rounded-lg p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Agenda Desa Hari Ini</h3>
                    <a href="{{ route('admin.master.agenda.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:underline">Kelola</a>
                </div>

                @if($stats['agenda_hari_ini']->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($stats['agenda_hari_ini'] as $agenda)
                            <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-900 truncate">{{ $agenda->judul }}</span>
                                    <span class="text-[10px] font-mono font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded px-1.5 py-0.2 shrink-0">
                                        {{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->format('H:i') }} WIB
                                    </span>
                                </div>
                                @if($agenda->lokasi)
                                    <p class="text-[11px] text-slate-500 truncate flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ $agenda->lokasi }}</span>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-5 text-center text-slate-400">
                        <svg class="w-6 h-6 mx-auto stroke-1 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs">Tidak ada agenda resmi hari ini</p>
                    </div>
                @endif
            </div>

            {{-- Widget: Progres Penanganan Pengaduan --}}
            <div class="bg-white border border-slate-200 rounded-lg p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Penyelesaian Pengaduan</h3>
                    <span class="text-[11px] font-semibold text-slate-600 font-mono">{{ $selesaiPct }}% Teratasi</span>
                </div>

                <div class="space-y-3">
                    {{-- Progress Bar Total --}}
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden flex">
                        @php
                            $pPending = $totalPengaduan > 0 ? ($stats['pengaduan_pending'] / $totalPengaduan) * 100 : 0;
                            $pProses  = $totalPengaduan > 0 ? ($stats['pengaduan_proses'] / $totalPengaduan) * 100 : 0;
                            $pSelesai = $totalPengaduan > 0 ? ($stats['pengaduan_selesai'] / $totalPengaduan) * 100 : 0;
                        @endphp
                        <div style="width: {{ $pSelesai }}%" class="bg-emerald-500" title="Selesai: {{ $stats['pengaduan_selesai'] }}"></div>
                        <div style="width: {{ $pProses }}%" class="bg-blue-500" title="Proses: {{ $stats['pengaduan_proses'] }}"></div>
                        <div style="width: {{ $pPending }}%" class="bg-amber-500" title="Pending: {{ $stats['pengaduan_pending'] }}"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 pt-1 text-center">
                        <div class="p-2 rounded bg-slate-50 border border-slate-200">
                            <span class="text-[10px] font-semibold text-slate-500 block">Pending</span>
                            <span class="text-sm font-bold text-amber-700 font-mono">{{ $stats['pengaduan_pending'] }}</span>
                        </div>
                        <div class="p-2 rounded bg-slate-50 border border-slate-200">
                            <span class="text-[10px] font-semibold text-slate-500 block">Proses</span>
                            <span class="text-sm font-bold text-blue-700 font-mono">{{ $stats['pengaduan_proses'] }}</span>
                        </div>
                        <div class="p-2 rounded bg-slate-50 border border-slate-200">
                            <span class="text-[10px] font-semibold text-slate-500 block">Selesai</span>
                            <span class="text-sm font-bold text-emerald-700 font-mono">{{ $stats['pengaduan_selesai'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Widget: Sebaran Kependudukan Dusun --}}
            <div class="bg-white border border-slate-200 rounded-lg p-4 sm:p-5 shadow-2xs space-y-3">
                <div class="pb-2.5 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Sebaran Wilayah Dusun</h3>
                    <p class="text-[11px] text-slate-500">Distribusi penduduk di seluruh dusun</p>
                </div>

                <div class="space-y-2.5">
                    @php
                        $dusunItems = $chartData['dusun'] ?? [];
                    @endphp
                    @foreach($dusunItems as $d)
                        @php
                            $dName = is_object($d) ? ($d->name ?? '') : ($d['name'] ?? '');
                            $dCount = is_object($d) ? ($d->count ?? 0) : ($d['count'] ?? 0);
                            $pct = $stats['total_penduduk'] > 0 ? round(($dCount / $stats['total_penduduk']) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs">
                                <span class="font-medium text-slate-700">{{ $dName }}</span>
                                <span class="text-slate-500 font-mono text-[11px]">{{ number_format($dCount) }} jiwa ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div style="width: {{ $pct }}%" class="bg-indigo-600 h-full"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Widget: Ringkasan Unit Ekonomi & Publikasi --}}
            <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-2xs">
                <div class="grid grid-cols-3 gap-2 text-center">
                    <a href="{{ route('admin.master.umkm.index') }}" class="p-2 rounded bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                        <span class="text-[10px] font-semibold text-slate-500 block">UMKM</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $stats['total_umkm'] }}</span>
                    </a>
                    <a href="{{ route('admin.master.bumdes.index') }}" class="p-2 rounded bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                        <span class="text-[10px] font-semibold text-slate-500 block">BUMDes</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $stats['total_bumdes'] }}</span>
                    </a>
                    <a href="{{ route('admin.master.berita.index') }}" class="p-2 rounded bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                        <span class="text-[10px] font-semibold text-slate-500 block">Berita</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $stats['total_berita'] }}</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Monthly Surat Trend Area/Bar Chart
    const monthlySuratData = @json($chartData['surat_monthly']);
    const trendCtx = document.getElementById('suratTrendChart');
    if (trendCtx && monthlySuratData) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: monthlySuratData.map(d => d.label),
                datasets: [{
                    label: 'Surat Terbit',
                    data: monthlySuratData.map(d => d.total),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ' ' + ctx.raw + ' surat terbit'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 11, family: 'inherit' }, color: '#64748b' },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 11, family: 'inherit' }, color: '#64748b', precision: 0 },
                        border: { display: false }
                    }
                }
            }
        });
    }

    // 2. Occupation Horizontal Bar Chart
    const occupationData = @json($chartData['occupation']);
    const occCtx = document.getElementById('occupationChart');
    if (occCtx) {
        new Chart(occCtx, {
            type: 'bar',
            data: {
                labels: occupationData.length ? occupationData.map(d => d.name) : ['Belum Ada Data'],
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: occupationData.length ? occupationData.map(d => d.count) : [0],
                    backgroundColor: '#3b82f6',
                    borderRadius: 3,
                    borderWidth: 0,
                    barThickness: 12
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ' ' + ctx.raw.toLocaleString() + ' jiwa'; }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10, family: 'inherit' }, color: '#94a3b8' },
                        border: { display: false }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'inherit' }, color: '#334155' },
                        border: { display: false }
                    }
                }
            }
        });
    }

});
</script>
@endpush
