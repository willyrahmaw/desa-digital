@extends('layouts.admin')

@section('title', 'Master Pengajuan Surat')
@section('breadcrumb-item', 'Pengajuan Surat')
@section('page-title', 'Pelayanan & Pengajuan Surat')

@section('page-actions')
<a href="{{ route('admin.master.surat.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    + Buat Pengajuan Baru
</a>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showApproveModal: false, 
    approveId: null, 
    approveNoSurat: '',
    approvePerangkatId: '',
    approveTemplateId: null,
    autoNomor: null,
    autoNomorLoading: false,
    showDeleteModal: false,
    deleteId: null,
    async fetchAutoNomor(templateId) {
        if (!templateId) return;
        this.autoNomorLoading = true;
        try {
            const res = await fetch(`{{ url('admin/master/surat/preview-nomor') }}?template_id=${templateId}`);
            const json = await res.json();
            this.autoNomor = json.nomor || null;
            if (json.nomor && !this.approveNoSurat) this.approveNoSurat = json.nomor;
        } catch(e) {
            this.autoNomor = null;
        }
        this.autoNomorLoading = false;
    }
}" 
@open-create-modal.window="showCreateModal = true"
@open-approve-modal.window="
    showApproveModal = true;
    approveId = $event.detail.id;
    approveNoSurat = $event.detail.no_surat;
    approvePerangkatId = $event.detail.perangkat_id;
    approveTemplateId = $event.detail.template_id;
    autoNomor = null;
    fetchAutoNomor($event.detail.template_id);
"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="suratTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Surat</th>
                        <th class="px-6 py-3">Penduduk</th>
                        <th class="px-6 py-3">Tipe / Layanan</th>
                        <th class="px-6 py-3">Tgl Pengajuan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($suratList as $item)
                        <tr>
                            <td class="px-6 py-4 text-slate-900 font-bold tracking-wider font-mono">
                                {{ $item->no_surat ?? 'Belum terbit' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold">{{ $item->penduduk->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400">NIK. {{ $item->penduduk_nik }}</div>
                                @if($item->penduduk?->dataSosial)
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @if($item->penduduk->dataSosial->layak_sktm)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Layak SKTM</span>
                                        @endif
                                        @if($item->penduduk->dataSosial->dtks)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">DTKS</span>
                                        @endif
                                        @if($item->penduduk->dataSosial->pkh)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">PKH</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-700 font-semibold">{{ $item->templateSurat->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400 max-w-xs truncate">{{ $item->keperluan }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->tanggal_pengajuan }}</td>
                            <td class="px-6 py-4">
                                @if($item->status_pengajuan === 'Pending')
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-600/20">Pending</span>
                                @elseif($item->status_pengajuan === 'Disetujui')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Disetujui</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-800 ring-1 ring-inset ring-rose-600/20">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                @if($item->status_pengajuan === 'Pending')
                                    <button @click="$dispatch('open-approve-modal', { 
                                        id: '{{ $item->id }}', 
                                        no_surat: '{{ $item->no_surat }}', 
                                        perangkat_id: '{{ $item->ttd_oleh_perangkat_id }}',
                                        template_id: '{{ $item->template_id }}'
                                    })" 
                                            class="inline-flex items-center rounded bg-emerald-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 transition-colors">
                                        Setujui & TTD
                                    </button>
                                    <form action="{{ route('admin.master.surat.reject', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center rounded bg-amber-50 px-2.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                @endif
                                
                                @if($item->status_pengajuan === 'Disetujui')
                                    <a href="{{ route('admin.master.surat.print', $item->id) }}" target="_blank" 
                                       class="inline-flex items-center rounded bg-slate-800 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                                        Cetak / Print
                                    </a>
                                @endif

                                <button @click="$dispatch('open-delete-modal', { id: '{{ $item->id }}' })" 
                                        class="inline-flex items-center rounded bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data pengajuan surat ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>



    <!-- Approve & Sign Modal (Alpine.js) -->
    <div x-show="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showApproveModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-base font-bold text-slate-900 mb-4">Persetujuan & Tanda Tangan Surat</h3>
            <form :action="'{{ url('admin/master/surat') }}/' + approveId + '/approve'" method="POST" class="space-y-4">
                @csrf
                
                {{-- Auto Nomor Banner --}}
                <div x-show="autoNomor" class="rounded-lg bg-indigo-700 p-3">
                    <p class="text-xs text-indigo-200 mb-0.5 font-medium">Nomor Otomatis dari Engine</p>
                    <p class="text-base font-black text-white font-mono tracking-wider" x-text="autoNomor"></p>
                </div>
                <div x-show="autoNomorLoading" class="text-xs text-slate-400 italic">Memuat nomor otomatis...</div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Surat Resmi <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_surat" x-model="approveNoSurat" required
                           class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none sm:text-sm"
                           placeholder="Nomor akan terisi otomatis jika format dikonfigurasi">
                    <p x-show="autoNomor" class="text-xs text-slate-400 mt-1">Nomor otomatis sudah terisi. Anda dapat menggantinya secara manual jika diperlukan.</p>
                </div>

                <div>
                    <label for="ttd_oleh_perangkat_id" class="block text-sm font-semibold text-slate-700 mb-2">Penandatangan (Perangkat Desa)</label>
                    <select id="ttd_oleh_perangkat_id" name="ttd_oleh_perangkat_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Penandatangan</option>
                        @foreach ($perangkatList as $pd)
                            <option value="{{ $pd->id }}">{{ $pd->nama }} ({{ $pd->jabatan->nama ?? '' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showApproveModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-emerald-600 text-white hover:bg-emerald-700">Tanda Tangani Surat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus permohonan surat ini dari sistem?</p>
            <form :action="'{{ url('admin/master/surat') }}/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-rose-600 text-white hover:bg-rose-700">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#suratTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        order: [[3, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});
</script>
@endpush
