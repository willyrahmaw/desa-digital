@extends('layouts.admin')

@section('title', 'Master Unit BUMDes')
@section('breadcrumb-item', 'BUMDes')
@section('page-title', 'Daftar Unit Usaha BUMDes')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Unit Baru
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
    editNama: '',
    editDeskripsi: '',
    editKetua: '',
    editStatus: 'aktif',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editNama = $event.detail.nama; editDeskripsi = $event.detail.deskripsi; editKetua = $event.detail.ketua; editStatus = $event.detail.status"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Search Card -->
    <x-card class="mb-6 py-4">
        <form action="{{ route('admin.master.bumdes-unit.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama unit atau penanggung jawab..." 
                       class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none sm:text-sm">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-800 text-white hover:bg-slate-900 transition-colors">
                    Cari
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.master.bumdes-unit.index') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="bumdesUnitTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Unit Usaha</th>
                        <th class="px-6 py-3">Penanggung Jawab (Ketua)</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($units as $item)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $item->nama }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-semibold">
                                {{ $item->ketua }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $item->deskripsi }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'aktif')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-800 ring-1 ring-inset ring-slate-600/20">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            nama: '{{ addslashes($item->nama) }}', 
                                            deskripsi: '{{ addslashes($item->deskripsi) }}',
                                            ketua: '{{ addslashes($item->ketua) }}',
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
                                Tidak ada data unit usaha BUMDes ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Unit Usaha BUMDes Baru</h3>
            <form action="{{ route('admin.master.bumdes-unit.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Unit Usaha" name="nama" required placeholder="Contoh: Unit Air Bersih" />
                <x-input label="Penanggung Jawab (Ketua)" name="ketua" required placeholder="Contoh: Heri Prasetyo" />

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Unit Usaha</label>
                    <textarea id="deskripsi" name="deskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm" placeholder="Penjelasan bidang usaha unit BUMDes..."></textarea>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status Unit</label>
                    <select id="status" name="status" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Unit Usaha BUMDes</h3>
            <form :action="'{{ url('admin/master/bumdes-unit') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Unit Usaha" name="nama" x-model="editNama" required />
                <x-input label="Penanggung Jawab (Ketua)" name="ketua" x-model="editKetua" required />

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Unit Usaha</label>
                    <textarea id="edit_deskripsi" name="deskripsi" x-model="editDeskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                </div>

                <div>
                    <label for="edit_status" class="block text-sm font-semibold text-slate-700 mb-2">Status Unit</label>
                    <select id="edit_status" name="status" x-model="editStatus" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
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
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus unit usaha BUMDes ini? Laporan kinerja yang terkait dengan unit ini juga akan dihapus permanen.</p>
            <form :action="'{{ url('admin/master/bumdes-unit') }}/' + deleteId" method="POST">
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
    $('#bumdesUnitTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [4] }
        ]
    });
});
</script>
@endpush
