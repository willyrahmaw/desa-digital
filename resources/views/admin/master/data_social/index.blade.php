@extends('layouts.admin')

@section('title', 'Master Data Sosial')
@section('breadcrumb-item', 'Data Sosial')
@section('page-title', 'Manajemen Kesejahteraan & Data Sosial')

@section('page-actions')
<button @click="$dispatch('open-create-modal')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
    Tambah Data Sosial
</button>
@endsection

@section('content')
<div x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    createDesil: '',
    editId: null, 
    editPendudukNik: '',
    editDtks: false,
    editPkh: false,
    editBpnt: false,
    editPbi: false,
    editKpr: false,
    editLayakSktm: false,
    editDesil: '',
    editKeterangan: '',
    showDeleteModal: false,
    deleteId: null,
    desilDescs: {
        1: 'Desil 1: 10% Rumah tangga dengan tingkat kesejahteraan terendah nasional (Sangat Miskin / Prioritas Utama Bansos).',
        2: 'Desil 2: 10% Rumah tangga tingkat kesejahteraan 11% - 20% (Miskin / Prioritas Bansos & PBI JK).',
        3: 'Desil 3: 10% Rumah tangga tingkat kesejahteraan 21% - 30% (Hampir Miskin / Berhak Layak SKTM & BPNT).',
        4: 'Desil 4: 10% Rumah tangga tingkat kesejahteraan 31% - 40% (Rentan Miskin / Sasaran JKN PBI).',
        5: 'Desil 5: 10% Rumah tangga tingkat kesejahteraan 41% - 50% (Menengah Bawah / Batas kecukupan dasar).',
        6: 'Desil 6: 10% Rumah tangga tingkat kesejahteraan 51% - 60% (Kategori ekonomi menengah).',
        7: 'Desil 7: 10% Rumah tangga tingkat kesejahteraan 61% - 70% (Kategori ekonomi menengah mandiri).',
        8: 'Desil 8: 10% Rumah tangga tingkat kesejahteraan 71% - 80% (Kategori menengah atas / mampu).',
        9: 'Desil 9: 10% Rumah tangga tingkat kesejahteraan 81% - 90% (Kategori ekonomi atas / kaya).',
        10: 'Desil 10: 10% Rumah tangga tingkat kesejahteraan tertinggi 91% - 100% (Sangat kaya / paling mampu).'
    }
}" 
@open-create-modal.window="showCreateModal = true; createDesil = ''"
@open-edit-modal.window="showEditModal = true; editId = $event.detail.id; editPendudukNik = $event.detail.penduduk_nik; editDtks = $event.detail.dtks; editPkh = $event.detail.pkh; editBpnt = $event.detail.bpnt; editPbi = $event.detail.pbi; editKpr = $event.detail.kpr; editLayakSktm = $event.detail.layak_sktm; editDesil = $event.detail.desil || ''; editKeterangan = $event.detail.keterangan"
@open-delete-modal.window="showDeleteModal = true; deleteId = $event.detail.id">

    <!-- Table Card -->
    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto p-4">
            <table id="socialTable" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Penduduk</th>
                        <th class="px-6 py-3 text-center">Desil (Kemensos)</th>
                        <th class="px-6 py-3 text-center">DTKS</th>
                        <th class="px-6 py-3 text-center">PKH</th>
                        <th class="px-6 py-3 text-center">BPNT</th>
                        <th class="px-6 py-3 text-center">PBI</th>
                        <th class="px-6 py-3 text-center">KPR</th>
                        <th class="px-6 py-3 text-center">Layak SKTM</th>
                        <th class="px-6 py-3">Verifikator</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($dataSosialList as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold">{{ $item->penduduk->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400 font-medium">NIK. {{ $item->penduduk_nik }}</div>
                                @if($item->keterangan)
                                    <div class="text-xs text-indigo-600 mt-0.5 truncate max-w-xs" title="{{ $item->keterangan }}">{{ $item->keterangan }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($item->desil)
                                    @if($item->desil <= 2)
                                        <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 border border-rose-200"
                                              title="{{ \App\Models\DataSosial::$desilKemensosMap[$item->desil]['desc'] ?? '' }}">
                                            Desil {{ $item->desil }} ({{ strtok(\App\Models\DataSosial::$desilKemensosMap[$item->desil]['label'] ?? '', '-') }})
                                        </span>
                                    @elseif($item->desil <= 4)
                                        <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 border border-amber-200"
                                              title="{{ \App\Models\DataSosial::$desilKemensosMap[$item->desil]['desc'] ?? '' }}">
                                            Desil {{ $item->desil }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200"
                                              title="{{ \App\Models\DataSosial::$desilKemensosMap[$item->desil]['desc'] ?? '' }}">
                                            Desil {{ $item->desil }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum di-set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->dtks)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">DTKS</span>
                                @else
                                    <span class="text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->pkh)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">PKH</span>
                                @else
                                    <span class="text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->bpnt)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">BPNT</span>
                                @else
                                    <span class="text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->pbi)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">PBI</span>
                                @else
                                    <span class="text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->kpr)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">KPR</span>
                                @else
                                    <span class="text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->layak_sktm)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20">Layak</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-800 ring-1 ring-inset ring-rose-600/20">Tidak Layak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-slate-700 font-medium">{{ $item->verifikator->name ?? '-' }}</div>
                                @if($item->tanggal_verifikasi)
                                    <div class="text-xs text-slate-400">{{ $item->tanggal_verifikasi }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button @click="$dispatch('open-edit-modal', { 
                                            id: '{{ $item->id }}', 
                                            penduduk_nik: '{{ $item->penduduk_nik }}', 
                                            dtks: {{ $item->dtks ? 'true' : 'false' }}, 
                                            pkh: {{ $item->pkh ? 'true' : 'false' }}, 
                                            bpnt: {{ $item->bpnt ? 'true' : 'false' }}, 
                                            pbi: {{ $item->pbi ? 'true' : 'false' }}, 
                                            kpr: {{ $item->kpr ? 'true' : 'false' }}, 
                                            layak_sktm: {{ $item->layak_sktm ? 'true' : 'false' }},
                                            desil: '{{ $item->desil ?? '' }}',
                                            keterangan: '{{ addslashes($item->keterangan) }}' 
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
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data sosial ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Create Modal (Alpine.js) -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showCreateModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <h3 class="text-base font-bold text-slate-900 mb-4">Tambah Data Sosial Penduduk</h3>
            <form action="{{ route('admin.master.data_social.store') }}" method="POST" class="space-y-4">
                @csrf
                <div x-data="{
                    searchWarga: '',
                    selectedNik: '',
                    selectedName: '',
                    openDropdown: false,
                    wargaList: [
                        @foreach ($penduduks as $p)
                            { nik: '{{ $p->nik }}', nama: '{{ addslashes($p->nama) }}', dusun: '{{ addslashes($p->dusun->nama ?? '-') }}' },
                        @endforeach
                    ],
                    get filteredWarga() {
                        if (!this.searchWarga) return this.wargaList.slice(0, 30);
                        const query = this.searchWarga.toLowerCase();
                        return this.wargaList.filter(w => 
                            w.nama.toLowerCase().includes(query) || w.nik.includes(query) || w.dusun.toLowerCase().includes(query)
                        ).slice(0, 50);
                    },
                    selectWarga(w) {
                        this.selectedNik = w.nik;
                        this.selectedName = w.nama + ' (' + w.nik + ')';
                        this.openDropdown = false;
                        this.searchWarga = '';
                    }
                }" class="relative">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Penduduk (Cari Nama / NIK)</label>
                    <input type="hidden" name="penduduk_nik" :value="selectedNik" required>
                    
                    <div @click="openDropdown = !openDropdown" 
                         class="cursor-pointer flex items-center justify-between w-full rounded border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm hover:border-indigo-600 sm:text-sm">
                        <span x-text="selectedName || '-- Cari & Pilih Warga (Ketik Nama / NIK) --'" :class="selectedName ? 'text-slate-900 font-semibold' : 'text-slate-400'"></span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Dropdown list with live search -->
                    <div x-show="openDropdown" @click.outside="openDropdown = false" 
                         class="absolute z-50 mt-1 max-h-64 w-full overflow-hidden rounded-md bg-white border border-slate-200 shadow-xl text-sm" style="display:none;">
                        <div class="p-2 border-b border-slate-100 bg-slate-50">
                            <input type="text" x-model="searchWarga" @input="openDropdown = true" placeholder="Ketik nama atau NIK warga..."
                                   class="w-full rounded border border-slate-300 px-3 py-1.5 text-xs text-slate-900 focus:border-indigo-600 focus:outline-none">
                        </div>
                        <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                            <template x-for="w in filteredWarga" :key="w.nik">
                                <li @click="selectWarga(w)" class="px-3 py-2.5 hover:bg-indigo-50 cursor-pointer flex justify-between items-center transition-colors">
                                    <div>
                                        <p class="font-semibold text-slate-900 text-xs" x-text="w.nama"></p>
                                        <p class="text-[11px] text-slate-500 font-mono" x-text="'NIK: ' + w.nik"></p>
                                    </div>
                                    <span class="text-[10px] text-indigo-700 font-semibold bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100" x-text="w.dusun"></span>
                                </li>
                            </template>
                            <template x-if="filteredWarga.length === 0">
                                <li class="px-3 py-4 text-center text-xs text-slate-400">Tidak ada warga dengan Nama / NIK tersebut.</li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Desil Kemensos Dropdown --}}
                <div>
                    <label for="desil" class="block text-sm font-semibold text-slate-700 mb-1">Tingkat Kesejahteraan Desil (Kemensos RI)</label>
                    <select id="desil" name="desil" x-model="createDesil" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Desil Kemensos (1 - 10)</option>
                        @foreach(\App\Models\DataSosial::$desilKemensosMap as $num => $info)
                            <option value="{{ $num }}">{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                    <div x-show="createDesil" class="mt-2 p-2.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs text-indigo-900">
                        <p class="font-bold">Deskripsi Kategori Kemensos:</p>
                        <p class="mt-0.5 leading-relaxed" x-text="desilDescs[createDesil] || ''"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="flex items-center">
                        <input id="dtks" name="dtks" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="dtks" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan DTKS</label>
                    </div>
                    <div class="flex items-center">
                        <input id="pkh" name="pkh" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="pkh" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan PKH</label>
                    </div>
                    <div class="flex items-center">
                        <input id="bpnt" name="bpnt" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="bpnt" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan BPNT</label>
                    </div>
                    <div class="flex items-center">
                        <input id="pbi" name="pbi" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="pbi" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan PBI-JK</label>
                    </div>
                    <div class="flex items-center">
                        <input id="kpr" name="kpr" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="kpr" class="ml-2 block text-sm text-slate-700 font-semibold">KPR / RLH</label>
                    </div>
                    <div class="flex items-center">
                        <input id="layak_sktm" name="layak_sktm" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                        <label for="layak_sktm" class="ml-2 block text-sm text-emerald-800 font-bold">Layak SKTM</label>
                    </div>
                </div>

                <x-input label="Keterangan Tambahan" name="keterangan" placeholder="Keterangan verifikasi..." />

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCreateModal = false" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (Alpine.js) -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.outside="showEditModal = false" class="bg-white border border-slate-200 rounded-lg p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <h3 class="text-base font-bold text-slate-900 mb-4">Edit Data Sosial Penduduk</h3>
            <form :action="'{{ url('admin/master/data_social') }}/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_penduduk_nik" class="block text-sm font-semibold text-slate-700 mb-2">Penduduk</label>
                    <select id="edit_penduduk_nik" name="penduduk_nik" x-model="editPendudukNik" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm bg-slate-50" readonly>
                        @foreach ($penduduks as $p)
                            <option value="{{ $p->nik }}">{{ $p->nama }} ({{ $p->nik }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Desil Kemensos Dropdown --}}
                <div>
                    <label for="edit_desil" class="block text-sm font-semibold text-slate-700 mb-1">Tingkat Kesejahteraan Desil (Kemensos RI)</label>
                    <select id="edit_desil" name="desil" x-model="editDesil" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Desil Kemensos (1 - 10)</option>
                        @foreach(\App\Models\DataSosial::$desilKemensosMap as $num => $info)
                            <option value="{{ $num }}">{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                    <div x-show="editDesil" class="mt-2 p-2.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs text-indigo-900">
                        <p class="font-bold">Deskripsi Kategori Kemensos:</p>
                        <p class="mt-0.5 leading-relaxed" x-text="desilDescs[editDesil] || ''"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="flex items-center">
                        <input id="edit_dtks" name="dtks" type="checkbox" :checked="editDtks" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="edit_dtks" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan DTKS</label>
                    </div>
                    <div class="flex items-center">
                        <input id="edit_pkh" name="pkh" type="checkbox" :checked="editPkh" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="edit_pkh" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan PKH</label>
                    </div>
                    <div class="flex items-center">
                        <input id="edit_bpnt" name="bpnt" type="checkbox" :checked="editBpnt" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="edit_bpnt" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan BPNT</label>
                    </div>
                    <div class="flex items-center">
                        <input id="edit_pbi" name="pbi" type="checkbox" :checked="editPbi" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="edit_pbi" class="ml-2 block text-sm text-slate-700 font-semibold">Bantuan PBI-JK</label>
                    </div>
                    <div class="flex items-center">
                        <input id="edit_kpr" name="kpr" type="checkbox" :checked="editKpr" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="edit_kpr" class="ml-2 block text-sm text-slate-700 font-semibold">KPR / RLH</label>
                    </div>
                    <div class="flex items-center">
                        <input id="edit_layak_sktm" name="layak_sktm" type="checkbox" :checked="editLayakSktm" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                        <label for="edit_layak_sktm" class="ml-2 block text-sm text-emerald-800 font-bold">Layak SKTM</label>
                    </div>
                </div>

                <x-input label="Keterangan Tambahan" name="keterangan" x-model="editKeterangan" />

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
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menghapus data sosial ini?</p>
            <form :action="'{{ url('admin/master/data_social') }}/' + deleteId" method="POST">
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
    $('#socialTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [8] }
        ]
    });
});
</script>
@endpush
