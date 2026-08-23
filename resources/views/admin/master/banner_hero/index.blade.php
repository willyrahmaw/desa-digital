@extends('layouts.admin')

@section('title', 'Kelola Banner Hero Slider')

@section('content')
<div x-data="{
    modalOpen: false,
    isEdit: false,
    editUrl: '',
    form: {
        judul: '',
        subjudul: '',
        tag: 'PROFIL DESA',
        link_url: '/profil',
        button_text: 'Jelajahi Profil Desa',
        urutan: 1,
        status_aktif: true
    },
    openCreate() {
        this.isEdit = false;
        this.form = {
            judul: '',
            subjudul: '',
            tag: 'PROFIL DESA',
            link_url: '/profil',
            button_text: 'Lihat Selengkapnya',
            urutan: 1,
            status_aktif: true
        };
        this.modalOpen = true;
    },
    openEdit(data, updateUrl) {
        this.isEdit = true;
        this.editUrl = updateUrl;
        this.form = {
            judul: data.judul,
            subjudul: data.subjudul || '',
            tag: data.tag || 'INFORMASI DESA',
            link_url: data.link_url || '/profil',
            button_text: data.button_text || 'Lihat Selengkapnya',
            urutan: data.urutan,
            status_aktif: Boolean(data.status_aktif)
        };
        this.modalOpen = true;
    }
}">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span>Banner Hero Slider</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold border border-slate-200">Landing Page</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Kelola gambar banner, judul, badge kategori, dan tombol slider utama pada Beranda Portal E-Desa.</p>
        </div>
        <button x-on:click="openCreate()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Banner Hero</span>
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-xs font-semibold flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    {{-- Main Table --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table id="bannerHeroTable" class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider">
                            <th class="p-3 w-12 text-center">Urutan</th>
                            <th class="p-3 w-28">Preview Gambar</th>
                            <th class="p-3">Judul Banner & Kategori</th>
                            <th class="p-3">Tombol & Tautan</th>
                            <th class="p-3 w-24 text-center">Status</th>
                            <th class="p-3 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($banners as $b)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 text-center font-bold text-slate-700 font-mono">{{ $b->urutan }}</td>
                                <td class="p-3">
                                    @if($b->gambar)
                                        <img src="{{ asset('storage/' . $b->gambar) }}" alt="{{ $b->judul }}" class="w-24 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs">
                                    @else
                                        <div class="w-24 h-14 bg-slate-100 text-slate-400 text-xs flex items-center justify-center rounded-lg border border-dashed border-slate-300">No Image</div>
                                    @endif
                                </td>
                                <td class="p-3 space-y-1">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold tracking-wide uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $b->tag ?: 'INFORMASI DESA' }}
                                    </span>
                                    <div class="font-bold text-slate-900 text-sm leading-snug">{{ $b->judul }}</div>
                                    <div class="text-slate-500 line-clamp-1 text-[11px]">{{ $b->subjudul ?? '-' }}</div>
                                </td>
                                <td class="p-3 space-y-1">
                                    <div class="font-semibold text-indigo-600">{{ $b->button_text ?: 'Lihat Selengkapnya' }}</div>
                                    <div class="text-slate-400 font-mono text-[10px] truncate max-w-xs">{{ $b->link_url ?: '/' }}</div>
                                </td>
                                <td class="p-3 text-center">
                                    @if($b->status_aktif)
                                        <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">Aktif</span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full font-bold text-[10px]">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center space-x-1">
                                    <button x-on:click="openEdit({{ json_encode($b) }}, '{{ route('admin.master.banner_hero.update', $b->id) }}')" 
                                            class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded font-medium text-[11px] shadow-2xs">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.master.banner_hero.destroy', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded font-medium text-[11px] shadow-2xs">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 italic">Belum ada banner hero yang dibuat. Klik tombol diatas untuk menambahkan banner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;" x-cloak>
        <div x-on:click.outside="modalOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-800" x-text="isEdit ? 'Edit Banner Hero' : 'Tambah Banner Hero Baru'"></h3>
                <button x-on:click="modalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <form :action="isEdit ? editUrl : '{{ route('admin.master.banner_hero.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Banner <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" x-model="form.judul" required placeholder="Contoh: Selamat Datang di Portal Resmi Desa Digital" 
                           class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subjudul / Deskripsi Singkat</label>
                    <textarea name="subjudul" x-model="form.subjudul" rows="2" placeholder="Penjelasan singkat atau slogan..." 
                              class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Badge Kategori</label>
                        <input type="text" name="tag" x-model="form.tag" placeholder="Contoh: PROFIL DESA, APBDES" 
                               class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tombol Aksi</label>
                        <input type="text" name="button_text" x-model="form.button_text" placeholder="Contoh: Jelajahi Layanan" 
                               class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tautan / Link Tujuan</label>
                    <input type="text" name="link_url" x-model="form.link_url" placeholder="Contoh: /profil, /layanan, /pengaduan, #apbdes" 
                           class="w-full text-xs font-mono rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        File Gambar Banner <span class="text-rose-500" x-show="!isEdit">*</span>
                    </label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp" :required="!isEdit"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-[11px] text-slate-400 mt-1">Rekomendasi rasio: 16:9 atau 1920x800 px. Format JPG, PNG, WEBP (Maks 5MB).</p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil <span class="text-rose-500">*</span></label>
                        <input type="number" name="urutan" x-model="form.urutan" required min="1" 
                               class="w-full text-xs font-bold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="inline-flex items-center cursor-pointer gap-2">
                            <input type="checkbox" name="status_aktif" value="1" x-model="form.status_aktif" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-slate-700">Status Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" x-on:click="modalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                        Simpan Banner
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#bannerHeroTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "search": "Cari Banner:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ banner",
            "paginate": { "first": "Pertama", "last": "Terakhir", "next": "Berikutnya", "previous": "Sebelumnya" }
        }
    });
});
</script>
@endpush
@endsection
