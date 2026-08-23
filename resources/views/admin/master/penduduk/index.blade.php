@extends('layouts.admin')

@section('title', 'Master Penduduk')
@section('breadcrumb-item', 'Penduduk')
@section('page-title', 'Daftar Penduduk')

@section('page-actions')
<a href="{{ route('admin.master.penduduk.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Penduduk
</a>
@endsection

@section('content')
<div x-data="{ showDeleteModal: false, deleteNik: '' }"
     @open-delete-modal.window="showDeleteModal = true; deleteNik = $event.detail.nik">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="pendudukTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Nama / NIK</th>
                        <th class="px-6 py-3">No. KK</th>
                        <th class="px-6 py-3">Jenis Kelamin</th>
                        <th class="px-6 py-3">Dusun / RT / RW</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @foreach ($penduduks as $p)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->foto)
                                    <img class="h-10 w-10 rounded-full object-cover border border-slate-200" src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        {{ substr($p->nama, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold">{{ $p->nama }}</div>
                                <div class="text-xs text-slate-400 font-medium tracking-wider">NIK. {{ $p->nik }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $p->no_kk ?? 'Tidak terdaftar' }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                @if($p->jenis_kelamin === 'L')
                                    Laki-laki
                                @else
                                    Perempuan
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $p->dusun->nama ?? '-' }} / RT {{ $p->rt->nomor ?? '-' }} / RW {{ $p->rw->nomor ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.master.penduduk.edit', $p->nik) }}" 
                                   class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </a>
                                <button @click="$dispatch('open-delete-modal', { nik: '{{ $p->nik }}' })" 
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

    <!-- Delete Modal Confirmation (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data penduduk ini? Aksi ini akan melakukan soft-delete pada data.</p>
            <form :action="'{{ url('admin/master/penduduk') }}/' + deleteNik" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#pendudukTable').DataTable({
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
