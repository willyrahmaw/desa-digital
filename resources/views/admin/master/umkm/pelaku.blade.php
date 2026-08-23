@extends('layouts.admin')

@section('title', 'Master Pelaku UMKM')
@section('breadcrumb-item', 'UMKM')
@section('page-title', 'Daftar Pelaku Usaha UMKM Desa')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Daftarkan Pelaku Baru
</button>
@endsection

@section('content')
<div class="flex border-b border-slate-200 mb-6 bg-white rounded-t-lg px-4 pt-3 shadow-sm">
    <a href="{{ route('admin.master.umkm.index') }}" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors {{ request()->routeIs('admin.master.umkm.index') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">Katalog Produk</a>
    <a href="{{ route('admin.master.umkm-pelaku.index') }}" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors {{ request()->routeIs('admin.master.umkm-pelaku.*') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">Pelaku Usaha</a>
    <a href="{{ route('admin.master.umkm-kategori.index') }}" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors {{ request()->routeIs('admin.master.umkm-kategori.*') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">Kategori Produk</a>
</div>

<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editId: null, 
    editNama: '',
    editNoHp: '',
    editAlamat: '',
    editUserId: '',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editNama = $event.detail.nama; editNoHp = $event.detail.no_hp; editAlamat = $event.detail.alamat; editUserId = $event.detail.user_id"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Search Card -->
    <x-card class="mb-6 py-4">
        <form action="{{ route('admin.master.umkm-pelaku.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau no. HP..." 
                       class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none sm:text-sm">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-800 text-white hover:bg-slate-900 transition-colors">
                    Cari
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.master.umkm-pelaku.index') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="umkmPelakuTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Pelaku Usaha</th>
                        <th class="px-6 py-3">No. HP / WhatsApp</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">Akun Terhubung</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($pelakus as $item)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $item->nama }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">
                                {{ $item->no_hp }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $item->alamat }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->user)
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                        {{ $item->user->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            nama: '{{ addslashes($item->nama) }}', 
                                            no_hp: '{{ $item->no_hp }}',
                                            alamat: '{{ addslashes($item->alamat) }}',
                                            user_id: '{{ $item->user_id }}' 
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
                                Tidak ada data pelaku UMKM ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Daftarkan Pelaku UMKM Baru</h3>
            <form action="{{ route('admin.master.umkm-pelaku.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Pelaku Usaha" name="nama" required placeholder="Contoh: Pak Budi Waluyo" />
                <x-input label="No. HP / WhatsApp" name="no_hp" required placeholder="Contoh: 081234567890" />
                
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm" placeholder="Contoh: RT. 02 RW. 01 Dusun Krajan Mulyo"></textarea>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-semibold text-slate-700 mb-2">Hubungkan ke Akun Pengguna (Optional)</label>
                    <select id="user_id" name="user_id" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">-- Pilih Akun --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Pelaku UMKM</h3>
            <form :action="'{{ url('admin/master/umkm-pelaku') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Pelaku Usaha" name="nama" x-model="editNama" required />
                <x-input label="No. HP / WhatsApp" name="no_hp" x-model="editNoHp" required />
                
                <div>
                    <label for="edit_alamat" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea id="edit_alamat" name="alamat" x-model="editAlamat" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                </div>

                <div>
                    <label for="edit_user_id" class="block text-sm font-semibold text-slate-700 mb-2">Hubungkan ke Akun Pengguna (Optional)</label>
                    <select id="edit_user_id" name="user_id" x-model="editUserId" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">-- Pilih Akun --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus pelaku usaha ini? Produk yang terkait dengannya juga akan dihapus.</p>
            <form :action="'{{ url('admin/master/umkm-pelaku') }}/' + deleteId" method="POST">
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
    $('#umkmPelakuTable').DataTable({
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
