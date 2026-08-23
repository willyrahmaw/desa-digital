@extends('layouts.admin')

@section('title', 'Manajemen APBDes — Transparansi Anggaran')
@section('breadcrumb-item', 'APBDes')
@section('page-title', 'Anggaran Pendapatan & Belanja Desa (APBDes)')

@php
    $appSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
    $namaDesa = $appSettings['nama_desa'] ?? 'Krajan Mulyo';
    $namaKades = $appSettings['nama_kades'] ?? 'Kepala Desa';
    $namaSekdes = $appSettings['nama_sekdes'] ?? 'Sekretaris Desa';

    $pendapatanItems = $items->where('tipe', 'pendapatan');
    $belanjaItems = $items->where('tipe', 'belanja');
    $pembiayaanItems = $items->where('tipe', 'pembiayaan');

    $totalPendapatanAnggaran = $summary['total_pendapatan_anggaran'] ?? 0;
    $totalPendapatanRealisasi = $summary['total_pendapatan_realisasi'] ?? 0;
    $pendapatanPct = $totalPendapatanAnggaran > 0 ? round(($totalPendapatanRealisasi / $totalPendapatanAnggaran) * 100, 1) : 0;

    $totalBelanjaAnggaran = $summary['total_belanja_anggaran'] ?? 0;
    $totalBelanjaRealisasi = $summary['total_belanja_realisasi'] ?? 0;
    $belanjaPct = $totalBelanjaAnggaran > 0 ? round(($totalBelanjaRealisasi / $totalBelanjaAnggaran) * 100, 1) : 0;

    $surplusDefisit = $totalPendapatanRealisasi - $totalBelanjaRealisasi;
    $pembiayaanNetto = $summary['total_pembiayaan_realisasi'] ?? 0;
    $silpa = $surplusDefisit + $pembiayaanNetto;

    // Filter Pendapatan Streams
    $padesItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'pades') || str_contains(strtolower($i->nama_item), 'asli'));
    $pades = $padesItems->sum('realisasi');
    $padesTarget = $padesItems->sum('anggaran');

    $danaDesaItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'dana desa') || str_contains(strtolower($i->nama_item), 'dana desa'));
    $danaDesa = $danaDesaItems->sum('realisasi');
    $danaDesaTarget = $danaDesaItems->sum('anggaran');

    $addItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'add') || str_contains(strtolower($i->nama_item), 'alokasi dana'));
    $add = $addItems->sum('realisasi');
    $addTarget = $addItems->sum('anggaran');

    $bagiHasilItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'bhpr') || str_contains(strtolower($i->nama_item), 'pajak') || str_contains(strtolower($i->nama_item), 'retribusi'));
    $bagiHasil = $bagiHasilItems->sum('realisasi');
    $bagiHasilTarget = $bagiHasilItems->sum('anggaran');

    $banprovItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'bantuan') || str_contains(strtolower($i->nama_item), 'prov') || str_contains(strtolower($i->nama_item), 'kab'));
    $banprov = $banprovItems->sum('realisasi');
    $banprovTarget = $banprovItems->sum('anggaran');

    $lainLainItems = $pendapatanItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), 'lain') || str_contains(strtolower($i->nama_item), 'lain'));
    $lainLain = $lainLainItems->sum('realisasi');
    $lainLainTarget = $lainLainItems->sum('anggaran');

    // 5 Bidang Belanja (Permendagri 20/2018)
    $bidang1 = $belanjaItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), '1.') || str_contains(strtolower($i->sub_kategori ?? ''), 'pemerintahan') || str_contains(strtolower($i->nama_item), 'pemerintah'));
    $b1Real = $bidang1->sum('realisasi');
    $b1Target = $bidang1->sum('anggaran');
    $b1Pct = $b1Target > 0 ? round(($b1Real / $b1Target) * 100, 1) : 0;

    $bidang2 = $belanjaItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), '2.') || str_contains(strtolower($i->sub_kategori ?? ''), 'pembangunan') || str_contains(strtolower($i->nama_item), 'pembangunan'));
    $b2Real = $bidang2->sum('realisasi');
    $b2Target = $bidang2->sum('anggaran');
    $b2Pct = $b2Target > 0 ? round(($b2Real / $b2Target) * 100, 1) : 0;

    $bidang3 = $belanjaItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), '3.') || str_contains(strtolower($i->sub_kategori ?? ''), 'pembinaan') || str_contains(strtolower($i->nama_item), 'pembinaan'));
    $b3Real = $bidang3->sum('realisasi');
    $b3Target = $bidang3->sum('anggaran');
    $b3Pct = $b3Target > 0 ? round(($b3Real / $b3Target) * 100, 1) : 0;

    $bidang4 = $belanjaItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), '4.') || str_contains(strtolower($i->sub_kategori ?? ''), 'pemberdayaan') || str_contains(strtolower($i->nama_item), 'pemberdayaan'));
    $b4Real = $bidang4->sum('realisasi');
    $b4Target = $bidang4->sum('anggaran');
    $b4Pct = $b4Target > 0 ? round(($b4Real / $b4Target) * 100, 1) : 0;

    $bidang5 = $belanjaItems->filter(fn($i) => str_contains(strtolower($i->sub_kategori ?? ''), '5.') || str_contains(strtolower($i->sub_kategori ?? ''), 'bencana') || str_contains(strtolower($i->nama_item), 'bencana') || str_contains(strtolower($i->nama_item), 'darurat'));
    $b5Real = $bidang5->sum('realisasi');
    $b5Target = $bidang5->sum('anggaran');
    $b5Pct = $b5Target > 0 ? round(($b5Real / $b5Target) * 100, 1) : 0;
@endphp

@section('content')
<div x-data="{ 
    activeTab: 'ringkasan',
    tableFilter: 'semua',
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editTahun: '{{ $tahun }}',
    editKategori: 'Pendapatan',
    editSubKategori: '',
    editNamaItem: '',
    editAnggaranRaw: 0,
    editAnggaranFormatted: '0',
    editRealisasiRaw: 0,
    editRealisasiFormatted: '0',
    editKeterangan: '',
    showDeleteModal: false,
    deleteId: null,

    // Dynamic Select Map (Permendagri 20/2018)
    createTipe: 'Pendapatan',
    createSubKategori: 'Pendapatan Asli Desa (PADes)',
    createAnggaranRaw: 0,
    createAnggaranFormatted: '0',
    createRealisasiRaw: 0,
    createRealisasiFormatted: '0',

    subKategoriMap: {
        'Pendapatan': [
            'Pendapatan Asli Desa (PADes)',
            'Pendapatan Transfer - Dana Desa (DD)',
            'Pendapatan Transfer - Alokasi Dana Desa (ADD)',
            'Pendapatan Transfer - Bagi Hasil Pajak & Retribusi (BHPR)',
            'Pendapatan Transfer - Bantuan Keuangan Prov/Kab',
            'Lain-lain Pendapatan Sah'
        ],
        'Belanja': [
            '1. Bidang Penyelenggaraan Pemerintahan Desa',
            '2. Bidang Pelaksanaan Pembangunan Desa',
            '3. Bidang Pembinaan Kemasyarakatan',
            '4. Bidang Pemberdayaan Masyarakat',
            '5. Bidang Penanggulangan Bencana, Keadaan Darurat & Mendesak Desa'
        ],
        'Pembiayaan': [
            'Penerimaan Pembiayaan (SILPA / Pencairan Dana Cadangan)',
            'Pengeluaran Pembiayaan (Penyertaan Modal BUMDes / Dana Cadangan)'
        ]
    },

    formatRupiah(val) {
        if (!val && val !== 0) return '';
        let numberString = val.toString().replace(/[^0-9]/g, '');
        return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    parseNumeric(val) {
        if (!val) return 0;
        return parseInt(val.toString().replace(/[^0-9]/g, ''), 10) || 0;
    },

    onCreateAnggaranInput(val) {
        this.createAnggaranRaw = this.parseNumeric(val);
        this.createAnggaranFormatted = this.formatRupiah(this.createAnggaranRaw);
    },

    onCreateRealisasiInput(val) {
        this.createRealisasiRaw = this.parseNumeric(val);
        this.createRealisasiFormatted = this.formatRupiah(this.createRealisasiRaw);
    },

    onEditAnggaranInput(val) {
        this.editAnggaranRaw = this.parseNumeric(val);
        this.editAnggaranFormatted = this.formatRupiah(this.editAnggaranRaw);
    },

    onEditRealisasiInput(val) {
        this.editRealisasiRaw = this.parseNumeric(val);
        this.editRealisasiFormatted = this.formatRupiah(this.editRealisasiRaw);
    }
}" 
@open-create-modal.window="showCreateModal = true; createTipe = 'Pendapatan'; createSubKategori = subKategoriMap['Pendapatan'][0]; createAnggaranRaw = 0; createAnggaranFormatted = '0'; createRealisasiRaw = 0; createRealisasiFormatted = '0'"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editTahun = $event.detail.tahun; editKategori = $event.detail.kategori; editSubKategori = $event.detail.sub_kategori || ''; editNamaItem = $event.detail.nama_item; editAnggaranRaw = $event.detail.anggaran; editAnggaranFormatted = formatRupiah($event.detail.anggaran); editRealisasiRaw = $event.detail.realisasi; editRealisasiFormatted = formatRupiah($event.detail.realisasi); editKeterangan = $event.detail.keterangan"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id"
class="space-y-6">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    {{-- ── 1. FISCAL EXECUTIVE HEADER & CONTROLS ──────────────── --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-1 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2.5 text-xs text-slate-500 mb-1 font-medium">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold border border-slate-200">
                    Permendagri No. 20/2018
                </span>
                <span>•</span>
                <span>Transparansi Keuangan {{ $namaDesa }}</span>
                <span>•</span>
                <span>Tahun Anggaran {{ $tahun }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Tata Kelola APBDes & Realisasi Anggaran
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Year Selector --}}
            <form action="{{ route('admin.master.apbdes.index') }}" method="GET" class="flex items-center gap-2">
                <label for="tahunSelect" class="text-xs font-semibold text-slate-500">Tahun:</label>
                <select id="tahunSelect" name="tahun" onchange="this.form.submit()" 
                        class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 font-bold font-mono focus:border-indigo-600 focus:outline-none shadow-2xs">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>

            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan</span>
            </button>

            <button @click="$dispatch('open-create-modal')" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Input Item APBDes</span>
            </button>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE POSTUR APBDES CARDS (4 CLEAN CARDS) ───── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Card 1: Pendapatan --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">1. Pendapatan Desa</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $pendapatanPct }}%
                    </span>
                </div>
                <div class="text-xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format($totalPendapatanRealisasi, 0, ',', '.') }}
                </div>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-emerald-600 h-full rounded-full" style="width: {{ min($pendapatanPct, 100) }}%"></div>
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Target Pagu:</span>
                <span class="font-mono font-semibold text-slate-700">Rp {{ number_format($totalPendapatanAnggaran, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Card 2: Belanja --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">2. Belanja Desa</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        {{ $belanjaPct }}%
                    </span>
                </div>
                <div class="text-xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format($totalBelanjaRealisasi, 0, ',', '.') }}
                </div>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($belanjaPct, 100) }}%"></div>
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Pagu Anggaran:</span>
                <span class="font-mono font-semibold text-slate-700">Rp {{ number_format($totalBelanjaAnggaran, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Card 3: Surplus / (Defisit) --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Surplus / (Defisit)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $surplusDefisit >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $surplusDefisit >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
                <div class="text-xl font-black font-mono tracking-tight {{ $surplusDefisit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                    Rp {{ number_format(abs($surplusDefisit), 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-2">Selisih Pendapatan & Belanja</p>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Status Fiskal:</span>
                <span class="font-semibold text-slate-700">{{ $surplusDefisit >= 0 ? 'Kas Berlebih' : 'Kas Defisit' }}</span>
            </div>
        </div>

        {{-- Card 4: Pembiayaan & SILPA --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">3. SILPA / Kas Sisa</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-700">
                        Netto
                    </span>
                </div>
                <div class="text-xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format(max(0, $silpa), 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-2">Sisa Lebih Perhitungan Anggaran</p>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Pembiayaan Netto:</span>
                <span class="font-mono font-semibold text-slate-700">Rp {{ number_format($pembiayaanNetto, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    {{-- ── 3. VIEW TOGGLE (RINGKASAN REALISASI VS TABEL MASTER) ─ --}}
    <div class="flex items-center justify-between bg-slate-100 p-1 rounded-xl border border-slate-200 max-w-md">
        <button @click="activeTab = 'ringkasan'" 
                :class="activeTab === 'ringkasan' ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 text-xs rounded-lg transition-all text-center">
            Struktur & Realisasi Anggaran
        </button>
        <button @click="activeTab = 'tabel'" 
                :class="activeTab === 'tabel' ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 text-xs rounded-lg transition-all text-center">
            Kelola Master Data ({{ $items->count() }})
        </button>
    </div>

    {{-- ── TAB 1: STRUKTUR & REALISASI ANGGARAN (CLEAN ENTERPRISE VIEW) --}}
    <div x-show="activeTab === 'ringkasan'" class="space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left: Belanja 5 Bidang (8 Cols) --}}
            <div class="lg:col-span-8 bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Realisasi Belanja Berdasarkan 5 Bidang</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Klasifikasi belanja desa sesuai regulasi Permendagri No. 20 Tahun 2018</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded">
                        Total: Rp {{ number_format($totalBelanjaRealisasi, 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-4">
                    
                    {{-- Bidang 1 --}}
                    <div class="p-3.5 rounded-lg border border-slate-100 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">1. Penyelenggaraan Pemerintahan Desa</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($b1Real, 0, ',', '.') }}</span>
                                <span class="text-[11px] font-mono text-slate-500">/ Rp {{ number_format($b1Target, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $b1Pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($b1Pct, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Bidang 2 --}}
                    <div class="p-3.5 rounded-lg border border-slate-100 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">2. Pelaksanaan Pembangunan Desa</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($b2Real, 0, ',', '.') }}</span>
                                <span class="text-[11px] font-mono text-slate-500">/ Rp {{ number_format($b2Target, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $b2Pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($b2Pct, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Bidang 3 --}}
                    <div class="p-3.5 rounded-lg border border-slate-100 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">3. Pembinaan Kemasyarakatan</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($b3Real, 0, ',', '.') }}</span>
                                <span class="text-[11px] font-mono text-slate-500">/ Rp {{ number_format($b3Target, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $b3Pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($b3Pct, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Bidang 4 --}}
                    <div class="p-3.5 rounded-lg border border-slate-100 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">4. Pemberdayaan Masyarakat Desa</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($b4Real, 0, ',', '.') }}</span>
                                <span class="text-[11px] font-mono text-slate-500">/ Rp {{ number_format($b4Target, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $b4Pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($b4Pct, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Bidang 5 --}}
                    <div class="p-3.5 rounded-lg border border-slate-100 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">5. Penanggulangan Bencana, Darurat & Mendesak</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($b5Real, 0, ',', '.') }}</span>
                                <span class="text-[11px] font-mono text-slate-500">/ Rp {{ number_format($b5Target, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $b5Pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min($b5Pct, 100) }}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right: Sumber Pendapatan Desa (4 Cols) --}}
            <div class="lg:col-span-4 bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800">Sumber Pendapatan Desa</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Rincian penerimaan kas transfer & PADes</p>
                </div>

                <div class="space-y-3">
                    
                    {{-- PADes --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">PADes (Pendapatan Asli)</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($padesTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($pades, 0, ',', '.') }}</span>
                    </div>

                    {{-- Dana Desa --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Dana Desa (DD - APBN)</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($danaDesaTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($danaDesa, 0, ',', '.') }}</span>
                    </div>

                    {{-- ADD --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Alokasi Dana Desa (ADD)</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($addTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($add, 0, ',', '.') }}</span>
                    </div>

                    {{-- Bagi Hasil Pajak --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Bagi Hasil Pajak & Retribusi</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($bagiHasilTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($bagiHasil, 0, ',', '.') }}</span>
                    </div>

                    {{-- Bantuan Keuangan --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Bantuan Keuangan Provinsi/Kab</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($banprovTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($banprov, 0, ',', '.') }}</span>
                    </div>

                    {{-- Lain-lain --}}
                    <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Lain-lain Pendapatan Sah</span>
                            <span class="text-[10px] text-slate-400">Pagu: Rp {{ number_format($lainLainTarget, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-emerald-700">Rp {{ number_format($lainLain, 0, ',', '.') }}</span>
                    </div>

                </div>
            </div>

        </div>

    </div>

    {{-- ── TAB 2: TABEL MASTER DATA APBDES (CRUD PENUH) ───────── --}}
    <div x-show="activeTab === 'tabel'" class="space-y-4" style="display: none;">
        
        <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700">Filter Jenis:</span>
                    <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-white text-xs">
                        <button @click="tableFilter = 'semua'" :class="tableFilter === 'semua' ? 'bg-slate-800 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all">Semua</button>
                        <button @click="tableFilter = 'pendapatan'" :class="tableFilter === 'pendapatan' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all">Pendapatan</button>
                        <button @click="tableFilter = 'belanja'" :class="tableFilter === 'belanja' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all">Belanja</button>
                        <button @click="tableFilter = 'pembiayaan'" :class="tableFilter === 'pembiayaan' ? 'bg-slate-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all">Pembiayaan</button>
                    </div>
                </div>

                <div class="text-xs text-slate-500 font-mono">
                    Total {{ $items->count() }} item anggaran tersimpan
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-4">Jenis Akun</th>
                            <th class="py-2.5 px-4">Sub-Kategori / Bidang</th>
                            <th class="py-2.5 px-4">Uraian Rincian Kegiatan</th>
                            <th class="py-2.5 px-4 text-right">Pagu Anggaran</th>
                            <th class="py-2.5 px-4 text-right">Realisasi</th>
                            <th class="py-2.5 px-4 text-center">Capaian</th>
                            <th class="py-2.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($items as $item)
                            @php
                                $itemPct = $item->anggaran > 0 ? round(($item->realisasi / $item->anggaran) * 100, 1) : 100;
                                $tipeKey = strtolower($item->tipe ?? '');
                            @endphp
                            <tr x-show="tableFilter === 'semua' || tableFilter === '{{ $tipeKey }}'" class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($tipeKey === 'pendapatan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">1. Pendapatan</span>
                                    @elseif($tipeKey === 'belanja')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">2. Belanja</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-200">3. Pembiayaan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-700 font-medium">
                                    {{ $item->sub_kategori ?: '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-slate-900 font-bold">{{ $item->nama_item }}</div>
                                    @if($item->keterangan)
                                        <div class="text-[11px] text-slate-400">{{ $item->keterangan }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-slate-700 whitespace-nowrap">
                                    Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($item->realisasi, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[10px] font-bold {{ $itemPct >= 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $itemPct }}%
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap space-x-1">
                                    <button @click="$dispatch('open-edit-modal', { 
                                                id: '{{ $item->id }}', 
                                                tahun: '{{ $item->tahun }}', 
                                                kategori: '{{ ucfirst($item->tipe) }}',
                                                sub_kategori: '{{ addslashes($item->sub_kategori ?? '') }}',
                                                nama_item: '{{ addslashes($item->nama_item) }}',
                                                anggaran: '{{ $item->anggaran }}',
                                                realisasi: '{{ $item->realisasi }}',
                                                keterangan: '{{ addslashes($item->keterangan ?? '') }}' 
                                            })" 
                                            class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-colors">
                                        Edit
                                    </button>
                                    <button @click="$dispatch('open-delete-modal', { id: '{{ $item->id }}' })" 
                                            class="inline-flex items-center px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] transition-colors">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 italic">Belum ada rincian data APBDes untuk tahun {{ $tahun }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── 4. OFFICIAL A4 PRINTABLE DOCUMENT (STANDAR LAPORAN FISKAL) --}}
    <div id="printableInfografis" class="hidden print:block font-sans text-slate-900 bg-white p-4">
        {{-- Formal Kop Surat --}}
        <div class="border-b-2 border-slate-900 pb-3 mb-4 text-center">
            <h3 class="text-xs font-bold uppercase tracking-wider">PEMERINTAH KABUPATEN {{ strtoupper($appSettings['nama_kabupaten'] ?? ($appSettings['kabupaten'] ?? 'NIRWANA RAYA')) }}</h3>
            <h2 class="text-sm font-bold uppercase tracking-wider">KECAMATAN {{ strtoupper($appSettings['nama_kecamatan'] ?? ($appSettings['kecamatan'] ?? 'ASTRAGUNA')) }}</h2>
            <h1 class="text-base font-black uppercase tracking-wide mt-0.5">PEMERINTAH {{ strtoupper($namaDesa) }}</h1>
            <p class="text-[10px] text-slate-600 mt-0.5 italic">{{ $appSettings['alamat_kantor'] ?? 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya 99881' }}</p>
        </div>

        <div class="text-center mb-4">
            <h2 class="text-sm font-black uppercase tracking-wide">LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DESA</h2>
            <p class="text-xs font-semibold text-slate-700">TAHUN ANGGARAN {{ $tahun }}</p>
        </div>

        {{-- Formal Financial Summary Table --}}
        <table class="w-full text-xs border border-slate-400 border-collapse mb-6">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-400 font-bold text-center">
                    <th class="border border-slate-400 p-2 text-left">URAIAN POSTUR APBDES</th>
                    <th class="border border-slate-400 p-2 text-right">ANGGARAN (RP)</th>
                    <th class="border border-slate-400 p-2 text-right">REALISASI (RP)</th>
                    <th class="border border-slate-400 p-2 text-center">PERSENTASE (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold bg-slate-50">
                    <td class="border border-slate-400 p-2">1. PENDAPATAN DESA</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($totalPendapatanAnggaran, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($totalPendapatanRealisasi, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-center font-mono">{{ $pendapatanPct }}%</td>
                </tr>
                <tr class="font-bold bg-slate-50">
                    <td class="border border-slate-400 p-2">2. BELANJA DESA</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($totalBelanjaAnggaran, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($totalBelanjaRealisasi, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-center font-mono">{{ $belanjaPct }}%</td>
                </tr>
                <tr class="font-bold">
                    <td class="border border-slate-400 p-2">SURPLUS / (DEFISIT)</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($totalPendapatanAnggaran - $totalBelanjaAnggaran, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format($surplusDefisit, 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-center font-mono">-</td>
                </tr>
                <tr class="font-bold">
                    <td class="border border-slate-400 p-2">3. PEMBIAYAAN NETTO & SILPA</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">-</td>
                    <td class="border border-slate-400 p-2 text-right font-mono">{{ number_format(max(0, $silpa), 0, ',', '.') }}</td>
                    <td class="border border-slate-400 p-2 text-center font-mono">-</td>
                </tr>
            </tbody>
        </table>

        {{-- Signatures --}}
        <div class="grid grid-cols-2 text-xs pt-6 text-center">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold mt-1">Kepala Desa {{ $namaDesa }}</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ $namaKades }}</p>
            </div>
            <div>
                <p>{{ $namaDesa }}, {{ date('d F Y') }}</p>
                <p class="font-bold mt-1">Sekretaris Desa / Pelaksana</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ $namaSekdes }}</p>
            </div>
        </div>
    </div>

    {{-- ── 5. CREATE MODAL (AUTO DOT FORMATTER NUMERIC) ───────── --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Rekam Item Data APBDes</h3>
                <span class="text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">Tahun {{ $tahun }}</span>
            </div>

            <form action="{{ route('admin.master.apbdes.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">1. Jenis Akun APBDes <span class="text-rose-500">*</span></label>
                    <select name="kategori" x-model="createTipe" @change="createSubKategori = subKategoriMap[createTipe][0]" required 
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                        <option value="Pendapatan">1. PENDAPATAN DESA</option>
                        <option value="Belanja">2. BELANJA DESA</option>
                        <option value="Pembiayaan">3. PEMBIAYAAN DESA</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">2. Sub-Kategori / Bidang <span class="text-rose-500">*</span></label>
                    <select name="sub_kategori" x-model="createSubKategori" required 
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                        <template x-for="opt in subKategoriMap[createTipe]" :key="opt">
                            <option :value="opt" x-text="opt"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">3. Uraian Rincian Kegiatan / Sumber Dana <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_item" placeholder="Misal: Pembangunan Jalan Lingkungan Dusun Krajan" required
                           class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pagu Anggaran (Rp) <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="anggaran" :value="createAnggaranRaw">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                            <input type="text" x-model="createAnggaranFormatted" @input="onCreateAnggaranInput($event.target.value)" required placeholder="0"
                                   class="block w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-slate-900 font-bold font-mono focus:border-indigo-600 focus:outline-none sm:text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="realisasi" :value="createRealisasiRaw">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                            <input type="text" x-model="createRealisasiFormatted" @input="onCreateRealisasiInput($event.target.value)" required placeholder="0"
                                   class="block w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-emerald-700 font-bold font-mono focus:border-indigo-600 focus:outline-none sm:text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Lokasi Kegiatan</label>
                    <input type="text" name="keterangan" placeholder="Catatan verifikasi atau nomor DPA..."
                           class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-xs">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Simpan Item APBDes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 6. EDIT MODAL (AUTO DOT FORMATTER NUMERIC) ─────────── --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-xl">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Edit Data Item APBDes</h3>
            <form :action="'{{ url('admin/master/apbdes') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="tahun" :value="editTahun">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">1. Jenis Akun APBDes <span class="text-rose-500">*</span></label>
                    <select name="kategori" x-model="editKategori" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                        <option value="Pendapatan">1. PENDAPATAN DESA</option>
                        <option value="Belanja">2. BELANJA DESA</option>
                        <option value="Pembiayaan">3. PEMBIAYAAN DESA</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">2. Sub-Kategori / Bidang <span class="text-rose-500">*</span></label>
                    <select name="sub_kategori" x-model="editSubKategori" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                        <template x-for="opt in subKategoriMap[editKategori] || []" :key="opt">
                            <option :value="opt" x-text="opt" :selected="opt === editSubKategori"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">3. Uraian Rincian Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_item" x-model="editNamaItem" required
                           class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 font-semibold focus:border-indigo-600 focus:outline-none sm:text-xs">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pagu Anggaran (Rp) <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="anggaran" :value="editAnggaranRaw">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                            <input type="text" x-model="editAnggaranFormatted" @input="onEditAnggaranInput($event.target.value)" required placeholder="0"
                                   class="block w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-slate-900 font-bold font-mono focus:border-indigo-600 focus:outline-none sm:text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="realisasi" :value="editRealisasiRaw">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                            <input type="text" x-model="editRealisasiFormatted" @input="onEditRealisasiInput($event.target.value)" required placeholder="0"
                                   class="block w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-emerald-700 font-bold font-mono focus:border-indigo-600 focus:outline-none sm:text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" x-model="editKeterangan"
                           class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-xs">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Perbarui Item APBDes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 7. DELETE MODAL ────────────────────────────────────── --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-xl p-6 max-w-sm w-full shadow-xl">
            <h3 class="text-sm font-bold text-slate-900 mb-1">Konfirmasi Hapus</h3>
            <p class="text-xs text-slate-500 mb-5">Apakah Anda yakin ingin menghapus rincian item anggaran APBDes ini?</p>
            <form :action="'{{ url('admin/master/apbdes') }}/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-lg bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #printableInfografis, #printableInfografis * {
        visibility: visible !important;
    }
    #printableInfografis {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        background: transparent !important;
    }
}
</style>
@endsection
