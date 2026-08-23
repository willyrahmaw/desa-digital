@extends('layouts.admin')

@section('title', 'Pengaduan Warga')
@section('breadcrumb-item', 'Pengaduan')
@section('page-title', 'Pengaduan & Aspirasi Warga')

@php
    $totalAll = $pengaduans->count();
    $totalPending = $pengaduans->filter(fn($p) => in_array(strtolower($p->status), ['pending']))->count();
    $totalProses = $pengaduans->filter(fn($p) => in_array(strtolower($p->status), ['proses', 'process']))->count();
    $totalSelesai = $pengaduans->filter(fn($p) => in_array(strtolower($p->status), ['selesai', 'resolved']))->count();
    $totalDitolak = $pengaduans->filter(fn($p) => in_array(strtolower($p->status), ['ditolak', 'rejected']))->count();
@endphp

@section('content')
<div x-data="{ 
    tableFilter: 'semua',
    showDetailModal: false,
    showDeleteModal: false,
    deleteId: null,

    detailData: {
        id: null,
        nomor_tiket: '',
        nama: '',
        nik: '',
        telepon: '',
        email: '',
        judul: '',
        kategori: '',
        isi: '',
        lokasi: '',
        status: 'pending',
        balasan: '',
        lampiran_url: '',
        tanggal: ''
    },

    openDetail(item) {
        this.detailData = {
            id: item.id,
            nomor_tiket: item.nomor_tiket || ('TKT-' + String(item.id).padStart(5, '0')),
            nama: (item.pelapor && item.pelapor.nama) ? item.pelapor.nama : (item.nama || 'Warga Anonim'),
            nik: item.pelapor_nik || item.nik || '-',
            telepon: item.telepon || '',
            email: item.email || '',
            judul: item.judul || '-',
            kategori: item.kategori || 'Umum',
            isi: item.isi || '',
            lokasi: item.lokasi || '',
            status: (item.status || 'pending').toLowerCase(),
            balasan: item.balasan || item.tanggapan || '',
            lampiran_url: item.lampiran ? ('{{ asset('storage') }}/' + item.lampiran) : '',
            tanggal: item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB' : '-'
        };
        this.showDetailModal = true;
    }
}" 
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id"
class="space-y-4">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    {{-- ── 1. CLEAN HEADER & STATUS TABS ───────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Daftar Pengaduan Masuk</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola laporan masyarakat, verifikasi bukti lapangan, dan kirimkan tindak lanjut resmi.</p>
        </div>

        {{-- Status Filter Tabs (Sleek Segmented Controls) --}}
        <div class="inline-flex rounded-lg border border-slate-200 p-1 bg-white shadow-2xs text-xs">
            <button @click="tableFilter = 'semua'" 
                    :class="tableFilter === 'semua' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-3 py-1.5 rounded-md transition-colors flex items-center gap-1.5">
                <span>Semua</span>
                <span class="text-[10px] font-mono px-1.5 py-0.2 rounded" :class="tableFilter === 'semua' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600'">{{ $totalAll }}</span>
            </button>
            <button @click="tableFilter = 'pending'" 
                    :class="tableFilter === 'pending' ? 'bg-amber-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-3 py-1.5 rounded-md transition-colors flex items-center gap-1.5">
                <span>Pending</span>
                @if($totalPending > 0)
                    <span class="text-[10px] font-mono px-1.5 py-0.2 rounded" :class="tableFilter === 'pending' ? 'bg-amber-700 text-white' : 'bg-amber-100 text-amber-800'">{{ $totalPending }}</span>
                @endif
            </button>
            <button @click="tableFilter = 'process'" 
                    :class="tableFilter === 'process' ? 'bg-blue-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-3 py-1.5 rounded-md transition-colors flex items-center gap-1.5">
                <span>Proses</span>
                @if($totalProses > 0)
                    <span class="text-[10px] font-mono px-1.5 py-0.2 rounded" :class="tableFilter === 'process' ? 'bg-blue-700 text-white' : 'bg-blue-100 text-blue-800'">{{ $totalProses }}</span>
                @endif
            </button>
            <button @click="tableFilter = 'resolved'" 
                    :class="tableFilter === 'resolved' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-3 py-1.5 rounded-md transition-colors flex items-center gap-1.5">
                <span>Selesai</span>
                <span class="text-[10px] font-mono px-1.5 py-0.2 rounded" :class="tableFilter === 'resolved' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600'">{{ $totalSelesai }}</span>
            </button>
        </div>
    </div>

    {{-- ── 2. DATA TABLE ───────────────────────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="pengaduanTable" class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">No. Tiket</th>
                        <th class="py-3 px-4">Pelapor</th>
                        <th class="py-3 px-4">Perihal & Uraian Aduan</th>
                        <th class="py-3 px-4 text-center">Lampiran</th>
                        <th class="py-3 px-4 text-center">Waktu Masuk</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($pengaduans as $item)
                        @php
                            $st = strtolower($item->status);
                            $stKey = in_array($st, ['proses', 'process']) ? 'process' : (in_array($st, ['selesai', 'resolved']) ? 'resolved' : (in_array($st, ['ditolak', 'rejected']) ? 'rejected' : 'pending'));
                            $namaPelapor = $item->pelapor->nama ?? ($item->nama ?? 'Warga');
                            $nikPelapor = $item->pelapor_nik ?: ($item->nik ?: '-');
                        @endphp
                        <tr x-show="tableFilter === 'semua' || tableFilter === '{{ $stKey }}'" class="hover:bg-slate-50/80 transition-colors">
                            
                            {{-- Tiket --}}
                            <td class="py-3.5 px-4 font-mono font-semibold text-slate-900 whitespace-nowrap">
                                {{ $item->nomor_tiket ?: 'TKT-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Pelapor --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">{{ $namaPelapor }}</div>
                                <div class="text-[11px] font-mono text-slate-400">NIK. {{ $nikPelapor }}</div>
                            </td>

                            {{-- Judul & Uraian --}}
                            <td class="py-3.5 px-4 max-w-sm">
                                <div class="font-semibold text-slate-900 line-clamp-1">{{ $item->judul }}</div>
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->isi }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $item->kategori ?: 'Umum' }}
                                    </span>
                                    @if($item->lokasi)
                                        <span class="text-[10px] text-slate-400 truncate">Lokasi: {{ $item->lokasi }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Lampiran Thumbnail --}}
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->lampiran)
                                    <button type="button" 
                                            @click="openDetail({{ json_encode($item) }})"
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-medium transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Foto Bukti</span>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>

                            {{-- Tanggal Masuk --}}
                            <td class="py-3.5 px-4 text-center text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if(in_array($st, ['selesai', 'resolved']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Selesai</span>
                                @elseif(in_array($st, ['proses', 'process']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">Proses</span>
                                @elseif(in_array($st, ['ditolak', 'rejected']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">Pending</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1.5">
                                <button type="button" 
                                        @click="openDetail({{ json_encode($item) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition-colors shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Tinjau</span>
                                </button>
                                <button type="button" 
                                        @click="$dispatch('open-delete-modal', { id: '{{ $item->id }}' })" 
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-rose-600 text-xs font-medium transition-colors cursor-pointer">
                                    Hapus
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">Belum ada rekaman laporan pengaduan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── 3. CLEAN DETAIL & RESPOND MODAL ─────────────────────── --}}
    <div x-show="showDetailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-2xs" style="display: none;">
        <div @click.outside="showDetailModal = false" class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
            
            {{-- Modal Header --}}
            <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50 sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold text-slate-800" x-text="detailData.nomor_tiket"></span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs text-slate-500 font-medium" x-text="detailData.kategori"></span>
                </div>
                <button type="button" @click="showDetailModal = false" class="text-slate-400 hover:text-slate-700 text-base font-bold p-1">&times;</button>
            </div>

            {{-- Modal Content --}}
            <div class="p-5 space-y-5 text-xs">
                
                {{-- Title & Timestamps --}}
                <div>
                    <h2 class="text-base font-bold text-slate-900" x-text="detailData.judul"></h2>
                    <div class="flex items-center gap-3 text-slate-500 text-[11px] mt-1">
                        <span x-text="detailData.tanggal"></span>
                        <template x-if="detailData.lokasi">
                            <span x-text="'Lokasi: ' + detailData.lokasi"></span>
                        </template>
                    </div>
                </div>

                {{-- Citizen Information --}}
                <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 border border-slate-200 rounded-lg">
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Pelapor</span>
                        <span class="font-bold text-slate-900" x-text="detailData.nama"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">NIK</span>
                        <span class="font-mono font-semibold text-slate-800" x-text="detailData.nik"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Kontak Telepon</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono text-slate-800" x-text="detailData.telepon || '-'"></span>
                            <template x-if="detailData.telepon">
                                <a :href="'https://wa.me/' + detailData.telepon.replace(/[^0-9]/g, '')" target="_blank" 
                                   class="text-[10px] font-semibold text-emerald-700 hover:underline">
                                    WhatsApp &rarr;
                                </a>
                            </template>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Email</span>
                        <span class="text-slate-700" x-text="detailData.email || '-'"></span>
                    </div>
                </div>

                {{-- Full Text --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Isi Laporan Warga</label>
                    <div class="p-3 bg-white border border-slate-200 rounded-lg text-slate-800 leading-relaxed whitespace-pre-line font-medium"
                         x-text="detailData.isi">
                    </div>
                </div>

                {{-- Photo Attachment --}}
                <template x-if="detailData.lampiran_url">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lampiran Foto Bukti</label>
                        <div class="rounded-lg border border-slate-200 overflow-hidden bg-slate-50 max-w-sm">
                            <a :href="detailData.lampiran_url" target="_blank" class="block group relative">
                                <img :src="detailData.lampiran_url" alt="Foto Bukti" class="w-full h-auto max-h-52 object-cover">
                                <div class="p-1.5 text-center text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 border-t border-slate-200">
                                    Buka Foto Ukuran Penuh &rarr;
                                </div>
                            </a>
                        </div>
                    </div>
                </template>

                {{-- Form Tanggapan & Status --}}
                <div class="pt-4 border-t border-slate-200">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tindak Lanjut & Tanggapan Resmi</h3>

                    <form :action="'{{ url('admin/master/pengaduan') }}/' + detailData.id + '/respond'" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Perbarui Status Penanganan <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="detailData.status" required 
                                    class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none bg-white">
                                <option value="pending">Menunggu Tindakan (Pending)</option>
                                <option value="process">Sedang Diproses (Investigasi / Lapangan)</option>
                                <option value="resolved">Selesai (Permasalahan Teratasi)</option>
                                <option value="rejected">Ditolak (Tidak Memenuhi Kriteria)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Catatan / Tanggapan Balasan ke Warga</label>
                            <textarea name="balasan" rows="3" x-model="detailData.balasan" placeholder="Tuliskan keterangan penyelesaian atau informasi dari pemerintah desa..."
                                      class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none leading-relaxed"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <button type="button" @click="showDetailModal = false" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Tutup</button>
                            <button type="submit" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-xs">
                                Simpan Tanggapan
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

    {{-- ── 4. DELETE MODAL ─────────────────────────────────────── --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-2xs" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-5 max-w-sm w-full shadow-xl">
            <h3 class="text-sm font-bold text-slate-900 mb-1">Hapus Data Pengaduan</h3>
            <p class="text-xs text-slate-500 mb-4">Apakah Anda yakin ingin menghapus data pengaduan ini secara permanen?</p>
            <form :action="'{{ url('admin/master/pengaduan') }}/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-3.5 py-1.5 text-xs font-bold rounded-lg bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
