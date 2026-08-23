@extends('layouts.admin')

@section('title', 'Penomoran Surat')
@section('breadcrumb-item', 'Penomoran Surat')
@section('page-title', 'Pengaturan Penomoran Surat')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    + Tambah Format
</button>
@endsection

@section('content')
<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    showDeleteModal: false,
    editId: null,
    editData: {},
    deleteId: null,

    // Live preview state
    createPreview: '...',
    editPreview: '...',
    previewUrl: '{{ route('admin.settings.penomoran.preview') }}',

    async fetchPreview(scope) {
        const form = scope === 'create'
            ? document.getElementById('create-format-form')
            : document.getElementById('edit-format-form');
        const data = Object.fromEntries(new FormData(form).entries());
        try {
            const res = await fetch(this.previewUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (scope === 'create') this.createPreview = json.preview;
            else this.editPreview = json.preview;
        } catch(e) {
            if (scope === 'create') this.createPreview = 'Gagal generate preview';
            else this.editPreview = 'Gagal generate preview';
        }
    },

    openEdit(row) {
        this.editId = row.id;
        this.editData = row;
        this.showEditModal = true;
        this.$nextTick(() => this.fetchPreview('edit'));
    }
}"
@open-create-modal.window="showCreateModal = true; $nextTick(() => fetchPreview('create'))"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 rounded bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Token Reference Card --}}
    <x-card class="mb-6 p-4">
        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Token Dinamis Yang Tersedia</h4>
        <div class="flex flex-wrap gap-2">
            @foreach(['{kode}','>{nomor}','{bulan}','{bulan_romawi}','{tahun}','{tahun_pendek}','{desa}','{kecamatan}','{kabupaten}','{provinsi}','{kode_pos}','{prefix}','{suffix}','{jenis}'] as $token)
                <code class="font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">{{ str_replace('>','',$token) }}</code>
            @endforeach
        </div>
        <p class="text-xs text-slate-400 mt-2">Contoh format: <code class="font-mono bg-slate-100 px-1 rounded">{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}</code> → <span class="font-semibold text-slate-700">470/001/Krajan Mulyo/VII/2026</span></p>
    </x-card>

    {{-- Format List Table --}}
    <x-card class="overflow-hidden p-0 mb-6">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Format Penomoran Terdaftar</h3>
            <form action="{{ route('admin.settings.penomoran.index') }}" method="GET" class="flex gap-2 items-center">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari format atau jenis..."
                       class="rounded border border-slate-300 px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-600 focus:outline-none w-60">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded bg-slate-800 text-white hover:bg-slate-900">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Format</th>
                        <th class="px-6 py-3">Jenis Surat</th>
                        <th class="px-6 py-3">Pola Format</th>
                        <th class="px-6 py-3">Reset</th>
                        <th class="px-6 py-3">Digit</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($formats as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->nama_format }}</td>
                            <td class="px-6 py-4">
                                <code class="text-xs font-mono bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">{{ $item->jenis_surat }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $item->format_nomor }}</code>
                            </td>
                            <td class="px-6 py-4 text-slate-500 capitalize">
                                @php $resetLabels = ['none'=>'Tidak Pernah','yearly'=>'Tahunan','monthly'=>'Bulanan','daily'=>'Harian','manual'=>'Manual'] @endphp
                                {{ $resetLabels[$item->reset_nomor] ?? $item->reset_nomor }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-slate-700">{{ $item->digit_nomor }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500 ring-1 ring-inset ring-slate-300">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <button @click="openEdit(@js([
                                    'id' => $item->id,
                                    'nama_format' => $item->nama_format,
                                    'jenis_surat' => $item->jenis_surat,
                                    'format_nomor' => $item->format_nomor,
                                    'separator' => $item->separator,
                                    'reset_nomor' => $item->reset_nomor,
                                    'digit_nomor' => $item->digit_nomor,
                                    'awalan' => $item->awalan ?? '',
                                    'akhiran' => $item->akhiran ?? '',
                                    'status' => $item->status ? '1' : '0',
                                ]))"
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
                                Belum ada format penomoran. Klik <strong>+ Tambah Format</strong> untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Create Modal --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <h3 class="text-base font-bold text-slate-900 mb-1">Tambah Format Penomoran</h3>
            <p class="text-xs text-slate-400 mb-5">Gunakan token dinamis dalam pola format.</p>

            {{-- Live Preview Banner --}}
            <div class="mb-5 rounded-lg bg-indigo-700 p-4">
                <p class="text-xs text-indigo-200 mb-1 font-medium uppercase tracking-wider">Preview Nomor Berikutnya</p>
                <p class="text-xl font-black text-white font-mono tracking-wider" x-text="createPreview">...</p>
            </div>

            <form id="create-format-form" action="{{ route('admin.settings.penomoran.store') }}" method="POST" class="space-y-4"
                  @input.debounce.400ms="fetchPreview('create')">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Format <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_format" required placeholder="Format SKTM Tahunan"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="jenis_surat" required placeholder="SKTM"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <p class="text-xs text-slate-400 mt-1">Harus unik per jenis surat</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Separator</label>
                        <input type="text" name="separator" value="/" maxlength="5" placeholder="/"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pola Format Nomor <span class="text-rose-500">*</span></label>
                        <input type="text" name="format_nomor" required placeholder="{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Reset Nomor <span class="text-rose-500">*</span></label>
                        <select name="reset_nomor" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="none">Tidak Pernah</option>
                            <option value="yearly" selected>Tahunan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="daily">Harian</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Digit Nomor <span class="text-rose-500">*</span></label>
                        <input type="number" name="digit_nomor" value="3" min="1" max="10" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Awalan (Prefix)</label>
                        <input type="text" name="awalan" placeholder="Kosongkan jika tidak ada"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Akhiran (Suffix)</label>
                        <input type="text" name="akhiran" placeholder="Kosongkan jika tidak ada"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2 flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                        <span class="text-sm font-semibold text-slate-700">Format Aktif</span>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan Format</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <h3 class="text-base font-bold text-slate-900 mb-1">Edit Format Penomoran</h3>
            <p class="text-xs text-slate-400 mb-5">Perubahan format akan berlaku untuk nomor berikutnya.</p>

            {{-- Live Preview Banner --}}
            <div class="mb-5 rounded-lg bg-emerald-700 p-4">
                <p class="text-xs text-emerald-200 mb-1 font-medium uppercase tracking-wider">Preview Nomor Berikutnya</p>
                <p class="text-xl font-black text-white font-mono tracking-wider" x-text="editPreview">...</p>
            </div>

            <form id="edit-format-form" :action="'{{ url('admin/settings/penomoran') }}/' + editId" method="POST" class="space-y-4"
                  @input.debounce.400ms="fetchPreview('edit')">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Format <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_format" :value="editData.nama_format" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="jenis_surat" :value="editData.jenis_surat" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Separator</label>
                        <input type="text" name="separator" :value="editData.separator" maxlength="5"
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pola Format Nomor <span class="text-rose-500">*</span></label>
                        <input type="text" name="format_nomor" :value="editData.format_nomor" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Reset Nomor <span class="text-rose-500">*</span></label>
                        <select name="reset_nomor" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            <option value="none" :selected="editData.reset_nomor === 'none'">Tidak Pernah</option>
                            <option value="yearly" :selected="editData.reset_nomor === 'yearly'">Tahunan</option>
                            <option value="monthly" :selected="editData.reset_nomor === 'monthly'">Bulanan</option>
                            <option value="daily" :selected="editData.reset_nomor === 'daily'">Harian</option>
                            <option value="manual" :selected="editData.reset_nomor === 'manual'">Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Digit Nomor <span class="text-rose-500">*</span></label>
                        <input type="number" name="digit_nomor" :value="editData.digit_nomor" min="1" max="10" required
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Awalan (Prefix)</label>
                        <input type="text" name="awalan" :value="editData.awalan"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Akhiran (Suffix)</label>
                        <input type="text" name="akhiran" :value="editData.akhiran"
                               class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                    <div class="col-span-2 flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editData.status == '1'" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                        <span class="text-sm font-semibold text-slate-700">Format Aktif</span>
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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus format penomoran ini? Data riwayat nomor yang sudah diterbitkan tidak akan terhapus.</p>
            <form :action="'{{ url('admin/settings/penomoran') }}/' + deleteId" method="POST">
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
