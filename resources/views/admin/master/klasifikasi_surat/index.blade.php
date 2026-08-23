@extends('layouts.admin')

@section('title', 'Klasifikasi Surat')
@section('breadcrumb-item', 'Klasifikasi Surat')
@section('page-title', 'Master Klasifikasi Surat')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    + Tambah Klasifikasi
</button>
@endsection

@section('content')
<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    showDeleteModal: false,
    editId: null,
    editNama: '',
    editKode: '',
    editKategori: '',
    editDeskripsi: '',
    editStatus: 'aktif',
    editUrutan: 0,
    deleteId: null,
    openEdit(id, nama, kode, kategori, deskripsi, status, urutan) {
        this.editId = id;
        this.editNama = nama;
        this.editKode = kode;
        this.editKategori = kategori;
        this.editDeskripsi = deskripsi;
        this.editStatus = status;
        this.editUrutan = urutan;
        this.showEditModal = true;
    }
}"
@open-create-modal.window="showCreateModal = true"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 rounded bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Table --}}
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="klasifikasiTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3 w-8">#</th>
                        <th class="px-6 py-3">Nama Surat</th>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Urutan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($classifications as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $item->urutan }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $item->nama }}</div>
                                @if($item->deskripsi)
                                    <div class="text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $item->deskripsi }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <code class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">{{ $item->kode }}</code>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->kategori }}</td>
                            <td class="px-6 py-4">
                                @if($item->status === 'aktif')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @elseif($item->status === 'nonaktif')
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-600/20">Nonaktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500 ring-1 ring-inset ring-slate-300">Diarsipkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-slate-500">{{ $item->urutan }}</td>
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <button @click="openEdit(
                                    '{{ $item->id }}',
                                    @js($item->nama),
                                    @js($item->kode),
                                    @js($item->kategori),
                                    @js($item->deskripsi ?? ''),
                                    '{{ $item->status }}',
                                    '{{ $item->urutan }}'
                                )"
                                class="inline-flex items-center rounded bg-indigo-50 px-2.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
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
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data klasifikasi surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Create Modal --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-lg w-full">
            <h3 class="text-base font-bold text-slate-900 mb-5">Tambah Klasifikasi Surat Baru</h3>
            <form action="{{ route('admin.master.klasifikasi_surat.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required placeholder="Surat Keterangan Tidak Mampu"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Klasifikasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" required placeholder="470" maxlength="50"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="kategori" required placeholder="Sosial"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" placeholder="Keterangan singkat tentang jenis surat ini..."
                                  class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="arsip">Diarsipkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" value="0" min="0"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-lg w-full">
            <h3 class="text-base font-bold text-slate-900 mb-5">Edit Klasifikasi Surat</h3>
            <form :action="'{{ url('admin/master/klasifikasi_surat') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" :value="editNama" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Klasifikasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" :value="editKode" required maxlength="50"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="kategori" :value="editKategori" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" x-bind:value="editDeskripsi"
                                  class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="aktif" :selected="editStatus === 'aktif'">Aktif</option>
                            <option value="nonaktif" :selected="editStatus === 'nonaktif'">Nonaktif</option>
                            <option value="arsip" :selected="editStatus === 'arsip'">Diarsipkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" :value="editUrutan" min="0"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus klasifikasi surat ini dari sistem?</p>
            <form :action="'{{ url('admin/master/klasifikasi_surat') }}/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#klasifikasiTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [6] }
        ]
    });
});
</script>
@endpush
