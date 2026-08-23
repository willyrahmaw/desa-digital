@extends('layouts.admin')

@section('title', 'Master RT')
@section('breadcrumb-item', 'RT')
@section('page-title', 'Daftar Rukun Tetangga (RT)')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah RT
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editRwId: '',
    editNomor: '',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editRwId = $event.detail.rw_id; editNomor = $event.detail.nomor"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="rtTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nomor RT</th>
                        <th class="px-6 py-3">RW Parent</th>
                        <th class="px-6 py-3">Dusun Parent</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($rts as $index => $rt)
                        <tr>
                            <td class="px-6 py-4 text-slate-500">{{ $rts->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-slate-900 font-medium">RT {{ $rt->nomor }}</td>
                            <td class="px-6 py-4 text-slate-700">RW {{ $rt->rw->nomor ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $rt->rw->dusun->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="$dispatch('open-edit-modal', { id: '{{ $rt->id }}', rw_id: '{{ $rt->rw_id }}', nomor: '{{ $rt->nomor }}' })" 
                                        class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </button>
                                <button @click="$dispatch('open-delete-modal', { id: '{{ $rt->id }}' })" 
                                        class="inline-flex items-center rounded bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data RT ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah RT Baru</h3>
            <form action="{{ route('admin.master.rt.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rw_id" class="block text-sm font-semibold text-slate-700 mb-2">RW Parent</label>
                    <select id="rw_id" name="rw_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih RW</option>
                        @foreach ($rws as $rw)
                            <option value="{{ $rw->id }}">RW {{ $rw->nomor }} (Dusun: {{ $rw->dusun->nama ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <x-input label="Nomor RT" name="nomor" placeholder="Contoh: 01, 003, dll." required />
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data RT</h3>
            <form :action="'{{ url('admin/master/rt') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_rw_id" class="block text-sm font-semibold text-slate-700 mb-2">RW Parent</label>
                    <select id="edit_rw_id" name="rw_id" x-model="editRwId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih RW</option>
                        @foreach ($rws as $rw)
                            <option value="{{ $rw->id }}">RW {{ $rw->nomor }} (Dusun: {{ $rw->dusun->nama ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <x-input label="Nomor RT" name="nomor" x-model="editNomor" required />
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal Confirmation (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data RT ini? Seluruh data penduduk yang terkait dengan RT ini akan mengalami gangguan relasi tempat tinggal.</p>
            <form :action="'{{ url('admin/master/rt') }}/' + deleteId" method="POST">
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
    $('#rtTable').DataTable({
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
