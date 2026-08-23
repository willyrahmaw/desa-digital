@extends('layouts.admin')

@section('title', 'Master Perangkat Desa')
@section('breadcrumb-item', 'Perangkat Desa')
@section('page-title', 'Daftar Perangkat Desa')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Perangkat Desa
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editNama: '',
    editNip: '',
    editJabatanId: '',
    editUserId: '',
    editStatusAktif: false,
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editNama = $event.detail.nama; editNip = $event.detail.nip; editJabatanId = $event.detail.jabatan_id; editUserId = $event.detail.user_id; editStatusAktif = $event.detail.status_aktif"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="perangkatTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Nama / NIP</th>
                        <th class="px-6 py-3">Jabatan</th>
                        <th class="px-6 py-3">Linked Account</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($perangkatList as $perangkat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($perangkat->foto)
                                    <img class="h-10 w-10 rounded-full object-cover border border-slate-200" src="{{ asset('storage/' . $perangkat->foto) }}" alt="{{ $perangkat->nama }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold border border-slate-200">
                                        {{ substr($perangkat->nama, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold">{{ $perangkat->nama }}</div>
                                <div class="text-xs text-slate-400">NIP. {{ $perangkat->nip ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $perangkat->jabatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $perangkat->user->email ?? 'Belum tertaut' }}</td>
                            <td class="px-6 py-4">
                                @if($perangkat->status_aktif)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $perangkat->id }}', 
                                            nama: '{{ $perangkat->nama }}', 
                                            nip: '{{ $perangkat->nip }}', 
                                            jabatan_id: '{{ $perangkat->jabatan_id }}', 
                                            user_id: '{{ $perangkat->user_id }}', 
                                            status_aktif: {{ $perangkat->status_aktif ? 'true' : 'false' }} 
                                        })" 
                                        class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </button>
                                <button @click="$dispatch('open-delete-modal', { id: '{{ $perangkat->id }}' })" 
                                        class="inline-flex items-center rounded bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data perangkat desa ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Perangkat Desa</h3>
            <form action="{{ route('admin.master.perangkat_desa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <x-input label="Nama Lengkap" name="nama" required />
                <x-input label="NIP (Nomor Induk Pegawai)" name="nip" placeholder="Opsional" />
                <div>
                    <label for="jabatan_id" class="block text-sm font-semibold text-slate-700 mb-2">Jabatan</label>
                    <select id="jabatan_id" name="jabatan_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-slate-700 mb-2">Tautkan Akun Login</label>
                    <select id="user_id" name="user_id" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Akun (Opsional)</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="foto_file" class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                    <input type="file" id="foto_file" name="foto_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <div class="flex items-center">
                    <input id="status_aktif" name="status_aktif" type="checkbox" checked value="1"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="status_aktif" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Perangkat Desa</h3>
            <form :action="'{{ url('admin/master/perangkat_desa') }}/' + editId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Lengkap" name="nama" x-model="editNama" required />
                <x-input label="NIP (Nomor Induk Pegawai)" name="nip" x-model="editNip" />
                <div>
                    <label for="edit_jabatan_id" class="block text-sm font-semibold text-slate-700 mb-2">Jabatan</label>
                    <select id="edit_jabatan_id" name="jabatan_id" x-model="editJabatanId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_user_id" class="block text-sm font-semibold text-slate-700 mb-2">Tautkan Akun Login</label>
                    <select id="edit_user_id" name="user_id" x-model="editUserId" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Akun (Opsional)</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_foto_file" class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Profil</label>
                    <input type="file" id="edit_foto_file" name="foto_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <div class="flex items-center">
                    <input id="edit_status_aktif" name="status_aktif" type="checkbox" :checked="editStatusAktif" value="1"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="edit_status_aktif" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data perangkat desa ini dari sistem?</p>
            <form :action="'{{ url('admin/master/perangkat_desa') }}/' + deleteId" method="POST">
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
    $('#perangkatTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ]
    });
});
</script>
@endpush
