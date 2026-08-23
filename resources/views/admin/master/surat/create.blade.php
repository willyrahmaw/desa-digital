@extends('layouts.admin')

@section('title', 'Buat Pengajuan Surat Baru')
@section('breadcrumb-item', 'Buat Pengajuan Surat')
@section('page-title', 'Buat & Generate Surat')

@section('page-actions')
<a href="{{ route('admin.master.surat.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors">
    ← Kembali ke Daftar Surat
</a>
@endsection

@section('content')
<div x-data="createSuratForm()">

    <form action="{{ route('admin.master.surat.store') }}" method="POST">
        @csrf

        {{-- Hidden Inputs --}}
        <input type="hidden" name="penduduk_nik" :value="selectedNik" required>
        <input type="hidden" name="template_surat_id" :value="selectedTemplateId" required>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ── LEFT COLUMN: WARGA & TEMPLATE SELECTION ───────────────── --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- 1. PILIH PEMOHON (WARGA) --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                            <h2 class="text-sm font-bold text-slate-900">Pilih Pemohon (Warga)</h2>
                        </div>
                        <span class="text-xs text-slate-400">Cari berdasarkan Nama atau NIK</span>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative mb-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.603 10.602z"/></svg>
                        </div>
                        <input type="text" x-model="searchWarga" placeholder="Ketik Nama atau NIK warga..."
                               class="block w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm">
                    </div>

                    {{-- Search Result Dropdown List --}}
                    <div x-show="searchWarga.length > 0 && (!selectedPenduduk || selectedPenduduk.nama.toLowerCase() !== searchWarga.toLowerCase())"
                         class="mb-4 max-h-48 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100 bg-white shadow-sm">
                        <template x-for="p in filteredPenduduks" :key="p.nik">
                            <div @click="selectPenduduk(p); searchWarga = p.nama"
                                 class="p-3 hover:bg-slate-50 cursor-pointer flex items-center justify-between transition-colors">
                                <div>
                                    <p class="text-sm font-bold text-slate-800" x-text="p.nama"></p>
                                    <p class="text-xs text-slate-400 font-mono" x-text="'NIK: ' + p.nik"></p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <template x-if="p.data_sosial && (p.data_sosial.layak_sktm === true || p.data_sosial.layak_sktm === 1)">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Layak SKTM</span>
                                    </template>
                                    <template x-if="p.data_sosial && (p.data_sosial.layak_sktm === false || p.data_sosial.layak_sktm === 0)">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Tidak Layak SKTM</span>
                                    </template>
                                    <template x-if="p.data_sosial && p.data_sosial.dtks">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">DTKS</span>
                                    </template>
                                    <span class="text-xs font-semibold text-indigo-600">Pilih →</span>
                                </div>
                            </div>
                        </template>
                        <div x-show="filteredPenduduks.length === 0" class="p-4 text-center text-xs text-slate-400">
                            Warga tidak ditemukan. Pastikan nama atau NIK sudah benar.
                        </div>
                    </div>

                    {{-- Selected Resident Card --}}
                    <template x-if="selectedPenduduk">
                        <div class="space-y-3">
                            <div class="bg-slate-50 border border-indigo-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-black flex items-center justify-center text-sm flex-shrink-0"
                                         x-text="selectedPenduduk.nama.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900" x-text="selectedPenduduk.nama"></p>
                                        <p class="text-xs font-mono text-slate-500" x-text="'NIK: ' + selectedPenduduk.nik"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="selectedPenduduk.alamat + (selectedPenduduk.dusun ? ', Dusun ' + selectedPenduduk.dusun.nama : '')"></p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <template x-if="selectedPenduduk.data_sosial && (selectedPenduduk.data_sosial.layak_sktm === true || selectedPenduduk.data_sosial.layak_sktm === 1)">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">Layak SKTM</span>
                                    </template>
                                    <template x-if="selectedPenduduk.data_sosial && (selectedPenduduk.data_sosial.layak_sktm === false || selectedPenduduk.data_sosial.layak_sktm === 0)">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20">Tidak Layak SKTM</span>
                                    </template>
                                    <template x-if="selectedPenduduk.data_sosial && selectedPenduduk.data_sosial.dtks">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-300">DTKS</span>
                                    </template>
                                    <button type="button" @click="selectedPenduduk = null; selectedNik = ''; searchWarga = ''"
                                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 ml-2">Ganti Pemohon</button>
                                </div>
                            </div>

                            {{-- Ineligible Warning Banner --}}
                            <template x-if="isWargaIneligibleSktm(selectedPenduduk)">
                                <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    <div>
                                        <p class="font-bold">Perhatian Khusus Status Sosial Warga</p>
                                        <p class="mt-0.5">Pemohon ini terdaftar sebagai <strong>TIDAK LAYAK SKTM</strong> pada Master Data Sosial Desa. Sistem akan menolak penerbitan Surat Keterangan Tidak Mampu (SKTM) untuk warga ini.</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- 2. PILIH SURAT YANG DAPAT DI-GENERATE --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Pilih Layanan Surat</h2>
                                <p class="text-xs text-slate-400">Daftar surat resmi yang dapat di-generate otomatis</p>
                            </div>
                        </div>
                        {{-- Filter Template --}}
                        <div class="w-full sm:w-64">
                            <input type="text" x-model="searchTemplate" placeholder="Cari tipe surat (SKTM, SKU...)"
                                   class="block w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none">
                        </div>
                    </div>

                    {{-- Template Cards Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <template x-for="t in filteredTemplates" :key="t.id">
                            <div @click="selectTemplate(t)"
                                 :class="selectedTemplateId == t.id ? (isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk) ? 'border-2 border-rose-500 bg-rose-50/50 shadow-sm' : 'border-2 border-indigo-600 bg-indigo-50/50 shadow-sm') : (isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk) ? 'border border-rose-200 bg-rose-50/20 opacity-75' : 'border border-slate-200 hover:border-indigo-300 bg-white hover:bg-slate-50')"
                                 class="rounded-xl p-4 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs flex-shrink-0"
                                             :class="isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk) ? 'bg-rose-100 text-rose-700' : ''">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        </div>
                                        <template x-if="isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk)">
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-rose-100 text-rose-700">Ditolak System</span>
                                        </template>
                                        <template x-if="!(isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk))">
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600"
                                                  x-text="t.kategori_surat || t.kode_surat || 'SURAT'"></span>
                                        </template>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900 leading-tight" x-text="t.nama"></p>
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-2" x-text="t.deskripsi || 'Surat resmi keterangan pelayanan publik desa'"></p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <template x-if="isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk)">
                                        <span class="text-xs font-bold text-rose-600">Tidak Dapat Diberikan</span>
                                    </template>
                                    <template x-if="!(isSktmTemplate(t) && isWargaIneligibleSktm(selectedPenduduk))">
                                        <span class="text-xs font-semibold" :class="selectedTemplateId == t.id ? 'text-indigo-700 font-bold' : 'text-slate-500'"
                                              x-text="selectedTemplateId == t.id ? '✓ Terpilih' : 'Pilih Surat'"></span>
                                    </template>
                                    <svg class="h-4 w-4" :class="selectedTemplateId == t.id ? 'text-indigo-600' : 'text-slate-300'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredTemplates.length === 0" class="p-8 text-center text-xs text-slate-400">
                        Tidak ada template surat yang cocok dengan pencarian Anda.
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN: SUMMARY & SUBMIT ─────────────────────── --}}
            <div class="lg:col-span-4 space-y-6">

                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm sticky top-20">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Ringkasan Pengajuan</h3>

                    {{-- Pemohon info --}}
                    <div class="mb-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Pemohon</p>
                        <template x-if="selectedPenduduk">
                            <div>
                                <p class="text-sm font-bold text-slate-900" x-text="selectedPenduduk.nama"></p>
                                <p class="text-xs font-mono text-slate-500" x-text="selectedPenduduk.nik"></p>
                            </div>
                        </template>
                        <template x-if="!selectedPenduduk">
                            <p class="text-xs text-rose-500 italic">Belum memilih pemohon</p>
                        </template>
                    </div>

                    {{-- Jenis Surat info --}}
                    <div class="mb-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Layanan Surat</p>
                        <template x-if="selectedTemplate">
                            <div>
                                <p class="text-sm font-bold text-indigo-700" x-text="selectedTemplate.nama"></p>
                                <p class="text-xs text-slate-400" x-text="selectedTemplate.kategori_surat || 'Surat Resmi'"></p>
                            </div>
                        </template>
                        <template x-if="!selectedTemplate">
                            <p class="text-xs text-rose-500 italic">Belum memilih jenis surat</p>
                        </template>
                    </div>

                    {{-- Ineligible Alert --}}
                    <template x-if="selectedTemplate && isSktmTemplate(selectedTemplate) && isWargaIneligibleSktm(selectedPenduduk)">
                        <div class="mb-4 bg-rose-50 border border-rose-200 rounded-lg p-3 text-xs text-rose-800">
                            <p class="font-bold">Sistem Menolak Pengajuan</p>
                            <p class="mt-0.5">Pemohon ini terdaftar sebagai <strong>TIDAK LAYAK SKTM</strong> pada Data Sosial Desa.</p>
                        </div>
                    </template>

                    {{-- Auto Number Banner --}}
                    <div x-show="selectedTemplate && !(isSktmTemplate(selectedTemplate) && isWargaIneligibleSktm(selectedPenduduk))" class="mb-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Nomor Surat Otomatis</p>
                        <div class="bg-indigo-700 text-white rounded-lg p-3">
                            <p class="text-[10px] text-indigo-200 font-medium">Format Engine Terbitan</p>
                            <p class="text-sm font-black font-mono tracking-wider mt-0.5" x-text="autoNomor || 'Membuat nomor...'"></p>
                        </div>
                    </div>

                    {{-- Keperluan Input --}}
                    <div class="mb-6">
                        <label for="keperluan" class="block text-xs font-semibold text-slate-700 mb-1">Keperluan / Alasan Pengajuan <span class="text-rose-500">*</span></label>
                        <textarea id="keperluan" name="keperluan" x-model="keperluan" required rows="3"
                                  placeholder="Contoh: Pengurusan beasiswa, kelengkapan berkas bank..."
                                  class="block w-full rounded-lg border border-slate-300 p-2.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none"></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            :disabled="isSubmitBlocked"
                            :class="isSubmitBlocked ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                            class="w-full py-3 px-4 rounded-lg font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                        Buat & Generate Surat
                    </button>
                    <p class="text-[10px] text-slate-400 text-center mt-2" x-text="isSubmitBlocked ? 'Permohonan ditolak sistem karena status kelayakan sosial' : 'Nomor surat akan diterbitkan otomatis setelah tombol diklik.'"></p>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
function createSuratForm() {
    return {
        searchWarga: '',
        selectedNik: '{{ $selectedNik ?? '' }}',
        selectedPenduduk: null,
        selectedTemplateId: '{{ $selectedTemplateId ?? '' }}',
        selectedTemplate: null,
        searchTemplate: '',
        keperluan: '',
        autoNomor: null,
        autoNomorLoading: false,
        penduduks: @json($penduduks),
        templates: @json($templates),

        init() {
            if (this.selectedNik) {
                this.selectedPenduduk = this.penduduks.find(p => p.nik === this.selectedNik) || null;
            }
            if (this.selectedTemplateId) {
                this.selectTemplateById(this.selectedTemplateId);
            }
        },

        isSktmTemplate(t) {
            if (!t) return false;
            const name = (t.nama || '').toLowerCase();
            const code = (t.kategori_surat || t.kode_surat || '').toLowerCase();
            return name.includes('tidak mampu') || name.includes('sktm') || code.includes('sktm');
        },

        isWargaIneligibleSktm(p) {
            if (!p || !p.data_sosial) return false;
            return p.data_sosial.layak_sktm === false || p.data_sosial.layak_sktm === 0 || p.data_sosial.layak_sktm === '0';
        },

        get isSubmitBlocked() {
            if (!this.selectedNik || !this.selectedTemplateId || !this.keperluan) return true;
            if (this.selectedTemplate && this.isSktmTemplate(this.selectedTemplate) && this.isWargaIneligibleSktm(this.selectedPenduduk)) {
                return true;
            }
            return false;
        },

        get filteredPenduduks() {
            if (!this.searchWarga) return this.penduduks.slice(0, 8);
            const q = this.searchWarga.toLowerCase();
            return this.penduduks.filter(p => 
                (p.nama && p.nama.toLowerCase().includes(q)) || 
                (p.nik && p.nik.toLowerCase().includes(q))
            ).slice(0, 10);
        },

        get filteredTemplates() {
            if (!this.searchTemplate) return this.templates;
            const q = this.searchTemplate.toLowerCase();
            return this.templates.filter(t => 
                (t.nama && t.nama.toLowerCase().includes(q)) || 
                (t.kode_surat && t.kode_surat.toLowerCase().includes(q)) ||
                (t.kategori_surat && t.kategori_surat.toLowerCase().includes(q))
            );
        },

        selectPenduduk(p) {
            this.selectedPenduduk = p;
            this.selectedNik = p.nik;
        },

        selectTemplate(t) {
            this.selectedTemplate = t;
            this.selectedTemplateId = t.id;
            this.fetchAutoNomor(t.id);
        },

        selectTemplateById(id) {
            const t = this.templates.find(item => item.id == id);
            if (t) this.selectTemplate(t);
        },

        async fetchAutoNomor(templateId) {
            if (!templateId) return;
            this.autoNomorLoading = true;
            try {
                const res = await fetch(`{{ url('admin/master/surat/preview-nomor') }}?template_id=${templateId}`);
                const json = await res.json();
                this.autoNomor = json.nomor || null;
            } catch(e) {
                this.autoNomor = null;
            }
            this.autoNomorLoading = false;
        }
    };
}
</script>
@endsection
