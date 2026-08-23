@extends('layouts.admin')

@section('title', 'Master Laporan BUMDes')
@section('breadcrumb-item', 'BUMDes')
@section('page-title', 'Laporan Kinerja Unit Usaha BUMDes')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Laporan Baru
</button>
@endsection

@section('content')
<div class="flex border-b border-slate-200 mb-6 bg-white rounded-t-lg px-4 pt-3 shadow-sm">
    <a href="{{ route('admin.master.bumdes.index') }}" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors {{ request()->routeIs('admin.master.bumdes.index') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">Laporan Kinerja</a>
    <a href="{{ route('admin.master.bumdes-unit.index') }}" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors {{ request()->routeIs('admin.master.bumdes-unit.*') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">Unit Usaha</a>
</div>

<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editUnitId: '',
    editTahun: '',
    editJenisLaporan: '',
    editKeterangan: '',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editUnitId = $event.detail.unit_id; editTahun = $event.detail.tahun; editJenisLaporan = $event.detail.jenis_laporan; editKeterangan = $event.detail.keterangan"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="bumdesTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Unit BUMDes</th>
                        <th class="px-6 py-3">Tahun Buku</th>
                        <th class="px-6 py-3">Jenis Laporan</th>
                        <th class="px-6 py-3">Lampiran Berkas</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($laporans as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold mb-1">{{ $item->bumdesUnit->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400">Pengelola: {{ $item->bumdesUnit->ketua ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-semibold">{{ $item->tahun }}</td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold mb-1">{{ $item->jenis_laporan }}</div>
                                <div class="text-xs text-slate-500 max-w-sm truncate">{{ $item->keterangan }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" 
                                       class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-900">
                                        📄 Download Berkas
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada berkas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            unit_id: '{{ $item->bumdes_unit_id }}', 
                                            tahun: '{{ $item->tahun }}',
                                            jenis_laporan: '{{ addslashes($item->jenis_laporan) }}',
                                            keterangan: '{{ addslashes($item->keterangan) }}' 
                                        })" 
                                        class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </button>
                                <button @click="$dispatch('open-delete-modal', { id: '{{ $item->id }}' })" 
                                        class="inline-flex items-center rounded bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data laporan BUMDes ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Create Modal (Alpine.js) -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Laporan BUMDes</h3>
            <form action="{{ route('admin.master.bumdes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="bumdes_unit_id" class="block text-sm font-semibold text-slate-700 mb-2">Unit Usaha BUMDes</label>
                    <select id="bumdes_unit_id" name="bumdes_unit_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <x-input label="Tahun Buku" name="tahun" type="number" required value="{{ date('Y') }}" />
                <x-input label="Jenis Laporan" name="jenis_laporan" required placeholder="Contoh: Laporan Keuangan Kuartal I" />
                <x-input label="Keterangan Ringkas" name="keterangan" placeholder="Opsional" />

                <div>
                    <label for="file_path_file" class="block text-sm font-semibold text-slate-700 mb-2">Unggah Berkas Laporan</label>
                    <input type="file" id="file_path_file" name="file_path_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCreateModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (Alpine.js) -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Laporan BUMDes</h3>
            <form :action="'{{ url('admin/master/bumdes') }}/' + editId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_bumdes_unit_id" class="block text-sm font-semibold text-slate-700 mb-2">Unit Usaha BUMDes</label>
                    <select id="edit_bumdes_unit_id" name="bumdes_unit_id" x-model="editUnitId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <x-input label="Tahun Buku" name="tahun" type="number" x-model="editTahun" required />
                <x-input label="Jenis Laporan" name="jenis_laporan" x-model="editJenisLaporan" required />
                <x-input label="Keterangan Ringkas" name="keterangan" x-model="editKeterangan" />

                <div>
                    <label for="edit_file_path_file" class="block text-sm font-semibold text-slate-700 mb-2">Ganti Berkas Laporan</label>
                    <input type="file" id="edit_file_path_file" name="file_path_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data laporan ini?</p>
            <form :action="'{{ url('admin/master/bumdes') }}/' + deleteId" method="POST">
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
    $('#bumdesTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [3, 4] }
        ]
    });
});
</script>
@endpush
