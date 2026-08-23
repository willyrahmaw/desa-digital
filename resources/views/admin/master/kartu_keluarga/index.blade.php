@extends('layouts.admin')

@section('title', 'Master Kartu Keluarga')
@section('breadcrumb-item', 'Kartu Keluarga')
@section('page-title', 'Daftar Kartu Keluarga')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Kartu Keluarga
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editNoKk: '',
    editAlamat: '',
    editDusunId: '',
    editRwId: '',
    editRtId: '',
    editKepalaKeluargaNik: '',
    showDeleteModal: false,
    deleteNoKk: ''
}" 
@open-create-modal.window="showCreateModal = true"
@open-edit-modal.window="showEditModal = true; editNoKk = $event.detail.no_kk; editAlamat = $event.detail.alamat; editDusunId = $event.detail.dusun_id; editRwId = $event.detail.rw_id; editRtId = $event.detail.rt_id; editKepalaKeluargaNik = $event.detail.kepala_keluarga_nik"
@open-delete-modal.window="showDeleteModal = true; deleteNoKk = $event.detail.no_kk">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="kkTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. KK</th>
                        <th class="px-6 py-3">Kepala Keluarga</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">Dusun / RW / RT</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($kkList as $kk)
                        <tr>
                            <td class="px-6 py-4 text-slate-900 font-bold tracking-wider">{{ $kk->no_kk }}</td>
                            <td class="px-6 py-4 text-slate-700 font-semibold">
                                {{ $kk->kepalaKeluarga->nama ?? 'Belum ditentukan' }}
                                @if($kk->kepala_keluarga_nik)
                                    <div class="text-xs text-slate-400 font-medium">NIK. {{ $kk->kepala_keluarga_nik }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $kk->alamat }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $kk->dusun->nama ?? '-' }} / RW {{ $kk->rw->nomor ?? '-' }} / RT {{ $kk->rt->nomor ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="$dispatch('open-edit-modal', { 
                                            no_kk: '{{ $kk->no_kk }}', 
                                            alamat: '{{ addslashes($kk->alamat) }}', 
                                            dusun_id: '{{ $kk->dusun_id }}', 
                                            rw_id: '{{ $kk->rw_id }}', 
                                            rt_id: '{{ $kk->rt_id }}',
                                            kepala_keluarga_nik: '{{ $kk->kepala_keluarga_nik }}' 
                                        })" 
                                        class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </button>
                                <button @click="$dispatch('open-delete-modal', { no_kk: '{{ $kk->no_kk }}' })" 
                                        class="inline-flex items-center rounded bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data Kartu Keluarga ditemukan.
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Kartu Keluarga</h3>
            <form action="{{ route('admin.master.kartu_keluarga.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nomor Kartu Keluarga (16 Digit)" name="no_kk" required maxlength="16" placeholder="Masukkan 16 digit No. KK..." />
                <x-input label="Alamat" name="alamat" required placeholder="Masukkan alamat lengkap..." />
                
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="dusun_id" class="block text-sm font-semibold text-slate-700 mb-2">Dusun</label>
                        <select id="dusun_id" name="dusun_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="">Pilih</option>
                            @foreach ($dusuns as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="rw_id" class="block text-sm font-semibold text-slate-700 mb-2">RW</label>
                        <select id="rw_id" name="rw_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="">Pilih</option>
                            @foreach ($rws as $rw)
                                <option value="{{ $rw->id }}">RW {{ $rw->nomor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="rt_id" class="block text-sm font-semibold text-slate-700 mb-2">RT</label>
                        <select id="rt_id" name="rt_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="">Pilih</option>
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}">RT {{ $rt->nomor }}</option>
                            @endforeach
                        </select>
                    </div>
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
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Kartu Keluarga</h3>
            <form :action="'{{ url('admin/master/kartu_keluarga') }}/' + editNoKk" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input label="Nomor Kartu Keluarga" name="no_kk" x-model="editNoKk" required readonly class="bg-slate-50" />
                <x-input label="Alamat" name="alamat" x-model="editAlamat" required />
                
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="edit_dusun_id" class="block text-sm font-semibold text-slate-700 mb-2">Dusun</label>
                        <select id="edit_dusun_id" name="dusun_id" x-model="editDusunId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($dusuns as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_rw_id" class="block text-sm font-semibold text-slate-700 mb-2">RW</label>
                        <select id="edit_rw_id" name="rw_id" x-model="editRwId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($rws as $rw)
                                <option value="{{ $rw->id }}">RW {{ $rw->nomor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_rt_id" class="block text-sm font-semibold text-slate-700 mb-2">RT</label>
                        <select id="edit_rt_id" name="rt_id" x-model="editRtId" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}">RT {{ $rt->nomor }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-input label="NIK Kepala Keluarga" name="kepala_keluarga_nik" x-model="editKepalaKeluargaNik" placeholder="Masukkan 16 digit NIK..." />

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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data Kartu Keluarga ini dari sistem?</p>
            <form :action="'{{ url('admin/master/kartu_keluarga') }}/' + deleteNoKk" method="POST">
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
    $('#kkTable').DataTable({
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
