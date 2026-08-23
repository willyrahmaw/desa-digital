@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('breadcrumb-item', 'User')
@section('page-title', 'Manajemen Akun Pengguna')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Pengguna Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editName: '',
    editEmail: '',
    editRoleId: '',
    editStatusAktif: false,
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editName = $event.detail.name; editEmail = $event.detail.email; editRoleId = $event.detail.role_id; editStatusAktif = $event.detail.status_aktif"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="userTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Pengguna</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Role / Kewenangan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                                    {{ $user->role->label ?? 'Admin' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status_aktif)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-500/10">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $user->id }}', 
                                            name: '{{ addslashes($user->name) }}', 
                                            email: '{{ $user->email }}', 
                                            role_id: '{{ $user->role_id }}', 
                                            status_aktif: {{ $user->status_aktif ? 'true' : 'false' }} 
                                        })" 
                                        class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </button>
                                <button @click="$dispatch('open-delete-modal', { id: '{{ $user->id }}' })" 
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
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Pengguna Baru</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Lengkap" name="name" required placeholder="Contoh: Muhammad Yusuf" />
                <x-input label="Alamat Email" name="email" type="email" required placeholder="nama@domain.com" />
                <x-input label="Password Akses" name="password" type="password" required placeholder="Masukkan password..." />
                
                <div>
                    <label for="role_id" class="block text-sm font-semibold text-slate-700 mb-2">Role Kewenangan</label>
                    <select id="role_id" name="role_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Kewenangan</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Pengguna</h3>
            <form :action="'{{ url('admin/user') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Lengkap" name="name" x-model="editName" required />
                <x-input label="Alamat Email" name="email" type="email" x-model="editEmail" required />
                <x-input label="Password Baru (Kosongkan jika tetap)" name="password" type="password" placeholder="Masukkan password baru..." />
                
                <div>
                    <label for="edit_role_id" class="block text-sm font-semibold text-slate-700 mb-2">Role Kewenangan</label>
                    <select id="edit_role_id" name="role_id" x-model="editRoleId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data pengguna ini?</p>
            <form :action="'{{ url('admin/user') }}/' + deleteId" method="POST">
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
    $('#userTable').DataTable({
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
