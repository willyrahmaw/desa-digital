@extends('layouts.admin')

@section('title', 'Master Parameter - ' . $label)
@section('breadcrumb-item', 'Parameter')
@section('page-title', 'Manajemen Parameter: ' . $label)

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Parameter
</button>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="{ 
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

    <!-- Left Sidebar: Selection List -->
    <div class="lg:col-span-1">
        <x-card class="p-4 space-y-1">
            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 mb-3">Tipe Parameter</h4>
            @php
                $types = [
                    'agama' => 'Agama',
                    'pendidikan' => 'Pendidikan',
                    'pekerjaan' => 'Pekerjaan',
                    'golongan_darah' => 'Golongan Darah',
                    'status_kawin' => 'Status Perkawinan',
                    'status_tinggal' => 'Status Tinggal',
                    'kewarganegaraan' => 'Kewarganegaraan',
                    'jabatan' => 'Jabatan Perangkat'
                ];
            @endphp
            @foreach($types as $key => $name)
                <a href="{{ route('admin.master.parameter.index', $key) }}" 
                   class="block px-3 py-2 text-sm font-semibold rounded transition-colors {{ $type === $key ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $name }}
                </a>
            @endforeach
        </x-card>
    </div>

    <!-- Right Content: Parameter Table -->
    <div class="lg:col-span-3">
        <!-- Table Card -->
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto p-4">
                <table id="parameterTable" class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nilai Parameter</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse ($items as $index => $item)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ $items->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-slate-900 font-semibold">{{ $item->nama }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="$dispatch('open-edit-modal', { id: '{{ $item->id }}', nama: '{{ $item->nama }}' })" 
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
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada parameter terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Create Modal (Alpine.js) -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Parameter Baru ({{ $label }})</h3>
            <form action="{{ route('admin.master.parameter.store', $type) }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nilai Parameter" name="nama" placeholder="Masukkan parameter..." required />
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Parameter ({{ $label }})</h3>
            <form :action="'{{ url('admin/master/parameter') }}/' + type + '/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nilai Parameter" name="nama" x-model="editNama" required />
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data parameter ini?</p>
            <form :action="'{{ url('admin/master/parameter') }}/' + type + '/' + deleteId" method="POST">
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
    $('#parameterTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [2] }
        ]
    });
});
</script>
@endpush
