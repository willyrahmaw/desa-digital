@extends('layouts.admin')

@section('title', 'Master Produk UMKM')
@section('breadcrumb-item', 'UMKM')
@section('page-title', 'Katalog Produk UMKM Desa')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Produk Baru
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
    editDeskripsi: '',
    editHarga: 0,
    editPelakuId: '',
    editKategoriId: '',
    showDeleteModal: false,
    deleteId: null
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editNama = $event.detail.nama; editDeskripsi = $event.detail.deskripsi; editHarga = $event.detail.harga; editPelakuId = $event.detail.pelaku_id; editKategoriId = $event.detail.kategori_id"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="umkmTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Nama Produk</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Pemilik / Pelaku</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($products as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->foto)
                                    <img class="h-10 w-10 rounded object-cover border border-slate-200" src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                                @else
                                    <div class="h-10 w-10 rounded bg-slate-100 flex items-center justify-center text-slate-400 font-bold border border-slate-200 text-[10px]">
                                        NO FOTO
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold mb-1">{{ $item->nama }}</div>
                                <div class="text-xs text-slate-400 max-w-sm truncate">{{ $item->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-900 font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-slate-700 font-medium">{{ $item->umkmPelaku->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $item->umkmKategori->nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            nama: '{{ addslashes($item->nama) }}', 
                                            deskripsi: '{{ addslashes($item->deskripsi) }}',
                                            harga: '{{ $item->harga }}',
                                            pelaku_id: '{{ $item->umkm_pelaku_id }}',
                                            kategori_id: '{{ $item->umkm_kategori_id }}' 
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
                                Tidak ada data produk UMKM ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Produk UMKM</h3>
            <form action="{{ route('admin.master.umkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <x-input label="Nama Produk" name="nama" required placeholder="Contoh: Kripik Tempe Krajan" />
                
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea id="deskripsi" name="deskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm" placeholder="Rincian deskripsi produk..."></textarea>
                </div>

                <x-input label="Harga Produk (IDR)" name="harga" type="number" required placeholder="Contoh: 15000" />
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="umkm_pelaku_id" class="block text-sm font-semibold text-slate-700 mb-2">Pelaku Usaha</label>
                        <select id="umkm_pelaku_id" name="umkm_pelaku_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="">Pilih Pelaku</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="umkm_kategori_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select id="umkm_kategori_id" name="umkm_kategori_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="foto_file" class="block text-sm font-semibold text-slate-700 mb-2">Foto Produk</label>
                    <input type="file" id="foto_file" name="foto_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Produk UMKM</h3>
            <form :action="'{{ url('admin/master/umkm') }}/' + editId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nama Produk" name="nama" x-model="editNama" required />
                
                <div>
                    <label for="edit_deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea id="edit_deskripsi" name="deskripsi" x-model="editDeskripsi" required rows="3" 
                              class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                </div>

                <x-input label="Harga Produk (IDR)" name="harga" type="number" x-model="editHarga" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_umkm_pelaku_id" class="block text-sm font-semibold text-slate-700 mb-2">Pelaku Usaha</label>
                        <select id="edit_umkm_pelaku_id" name="umkm_pelaku_id" x-model="editPelakuId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_umkm_kategori_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select id="edit_umkm_kategori_id" name="umkm_kategori_id" x-model="editKategoriId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="edit_foto_file" class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Produk</label>
                    <input type="file" id="edit_foto_file" name="foto_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus produk ini dari katalog?</p>
            <form :action="'{{ url('admin/master/umkm') }}/' + deleteId" method="POST">
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
    $('#umkmTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [0, 4] }
        ]
    });
});
</script>
@endpush
