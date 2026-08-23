@extends('layouts.admin')

@section('title', 'Master Agenda Kegiatan')
@section('breadcrumb-item', 'Agenda')
@section('page-title', 'Agenda & Jadwal Kegiatan Desa')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Agenda Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editJudul: '',
    editDeskripsi: '',
    editTanggalMulai: '',
    editTanggalSelesai: '',
    editLokasi: '',
    editStatus: 'Rencana',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editJudul = $event.detail.judul; editDeskripsi = $event.detail.deskripsi; editTanggalMulai = $event.detail.tanggal_mulai; editTanggalSelesai = $event.detail.tanggal_selesai; editLokasi = $event.detail.lokasi; editStatus = $event.detail.status"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="agendaTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Kegiatan</th>
                        <th class="px-6 py-3">Waktu Pelaksanaan</th>
                        <th class="px-6 py-3">Lokasi</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($agendas as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold mb-1">{{ $item->judul }}</div>
                                <div class="text-xs text-slate-500 max-w-sm truncate">{{ $item->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                <div class="font-semibold">{{ $item->tanggal_mulai }}</div>
                                <div class="text-xs text-slate-400">s/d {{ $item->tanggal_selesai }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->lokasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'Rencana')
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-800 ring-1 ring-inset ring-slate-600/20">Rencana</span>
                                @elseif($item->status === 'Berjalan')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-800 ring-1 ring-inset ring-blue-600/20">Berjalan</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            judul: '{{ addslashes($item->judul) }}', 
                                            deskripsi: '{{ addslashes($item->deskripsi) }}', 
                                            tanggal_mulai: '{{ $item->tanggal_mulai }}', 
                                            tanggal_selesai: '{{ $item->tanggal_selesai }}', 
                                            lokasi: '{{ addslashes($item->lokasi) }}',
                                            status: '{{ $item->status }}' 
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
                                Tidak ada data agenda kegiatan ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Agenda Kegiatan</h3>
            <form action="{{ route('admin.master.agenda.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Kegiatan" name="judul" required placeholder="Masukkan nama..." />
                
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Kegiatan</label>
                    <textarea id="deskripsi" name="deskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm" placeholder="Rincian agenda kegiatan..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Tanggal Mulai" name="tanggal_mulai" type="datetime-local" required />
                    <x-input label="Tanggal Selesai" name="tanggal_selesai" type="datetime-local" required />
                </div>

                <x-input label="Lokasi / Tempat" name="lokasi" required placeholder="Contoh: Balai Desa Krajan Mulyo" />
                
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select id="status" name="status" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="Rencana">Direncanakan</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Agenda Kegiatan</h3>
            <form :action="'{{ url('admin/master/agenda') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Kegiatan" name="judul" x-model="editJudul" required />
                
                <div>
                    <label for="edit_deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Kegiatan</label>
                    <textarea id="edit_deskripsi" name="deskripsi" x-model="editDeskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Tanggal Mulai" name="tanggal_mulai" type="text" x-model="editTanggalMulai" required />
                    <x-input label="Tanggal Selesai" name="tanggal_selesai" type="text" x-model="editTanggalSelesai" required />
                </div>

                <x-input label="Lokasi / Tempat" name="lokasi" x-model="editLokasi" required />
                
                <div>
                    <label for="edit_status" class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select id="edit_status" name="status" x-model="editStatus" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="Rencana">Rencana</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data agenda ini?</p>
            <form :action="'{{ url('admin/master/agenda') }}/' + deleteId" method="POST">
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
    $('#agendaTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [4] }
        ]
    });
});
</script>
@endpush
