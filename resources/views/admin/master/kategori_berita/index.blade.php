@extends('layouts.admin')

@section('title', 'Master Kategori Berita')
@section('breadcrumb-item', 'Kategori Berita')
@section('page-title', 'Kelola Kategori Berita & Artikel')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150 shadow-sm">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Kategori Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editNama: '',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editNama = $event.detail.nama"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="kategoriTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Kategori</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Jumlah Artikel</th>
                        <th class="px-6 py-3">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @foreach ($categories as $item)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $item->nama }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $item->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                                    {{ $item->beritas_count }} artikel
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            nama: '{{ addslashes($item->nama) }}'
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Create Modal (Alpine.js) -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full shadow-xl">
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Kategori Berita</h3>
            <form action="{{ route('admin.master.kategori_berita.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Kategori" name="nama" required placeholder="Contoh: Pengumuman Desa" />

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCreateModal = false" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (Alpine.js) -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full shadow-xl">
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Kategori Berita</h3>
            <form :action="'{{ url('admin/master/kategori_berita') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Kategori" name="nama" x-model="editNama" required />

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full shadow-lg">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus kategori berita ini?</p>
            <form :action="'{{ url('admin/master/kategori_berita') }}/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded bg-rose-600 text-white hover:bg-rose-700">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#kategoriTable').DataTable({
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
