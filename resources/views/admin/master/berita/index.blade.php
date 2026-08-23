@extends('layouts.admin')

@section('title', 'Master Berita Desa')
@section('breadcrumb-item', 'Berita')
@section('page-title', 'Kabar & Berita Desa')

@section('page-actions')
<a href="{{ route('admin.master.berita.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150 shadow-sm">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tulis Berita Baru
</a>
@endsection

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    deleteId: null 
}" 
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="beritaTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3">Judul Berita</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Tgl Publikasi</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @foreach ($beritas as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->gambar || $item->cover_image)
                                    <img class="h-10 w-16 object-cover rounded border border-slate-200" src="{{ asset('storage/' . ($item->gambar ?: $item->cover_image)) }}" alt="{{ $item->judul }}">
                                @else
                                    <div class="h-10 w-16 rounded bg-slate-100 flex items-center justify-center text-slate-400 font-bold border border-slate-200 text-[10px]">
                                        NO IMAGE
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold max-w-md truncate">{{ $item->judul }}</div>
                                <div class="text-xs text-slate-400">Oleh: {{ $item->user->name ?? 'Admin' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->kategoriBerita->nama ?? ($item->kategori->nama ?? '-') }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->tanggal_publikasi }}</td>
                            <td class="px-6 py-4">
                                @if($item->status === 'Publikasi' || $item->status === 'published')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Publikasi</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.master.berita.edit', $item->id) }}"
                                   class="inline-flex items-center rounded bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Edit
                                </a>
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

    <!-- Delete Modal (Alpine.js) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showDeleteModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-sm w-full shadow-lg">
            <h3 class="text-base font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data berita ini dari sistem?</p>
            <form :action="'{{ url('admin/master/berita') }}/' + deleteId" method="POST">
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
    $('#beritaTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        order: [[3, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ]
    });
});
</script>
@endpush
