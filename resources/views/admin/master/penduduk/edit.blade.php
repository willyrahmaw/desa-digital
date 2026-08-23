@extends('layouts.admin')

@section('title', 'Edit Penduduk')
@section('breadcrumb-item', 'Edit')
@section('page-title', 'Edit Data Penduduk: ' . $penduduk->nama)

@section('content')
<x-card class="max-w-4xl mx-auto">
    @if ($errors->any())
        <div class="mb-6 p-4 rounded bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <h4 class="font-bold mb-1">Periksa kembali data Anda:</h4>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.master.penduduk.update', $penduduk->nik) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Group 1: Identitas Diri -->
        <div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Identitas Utama</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="NIK (Nomor Induk Kependudukan)" name="nik" placeholder="Masukkan 16 digit NIK..." required readonly class="bg-slate-50" value="{{ old('nik', $penduduk->nik) }}" />
                
                <div>
                    <label for="no_kk" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Kartu Keluarga (KK)</label>
                    <select id="no_kk" name="no_kk" class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Belum Memiliki KK / Opsional</option>
                        @foreach ($kkList as $kk)
                            <option value="{{ $kk->no_kk }}" {{ old('no_kk', $penduduk->no_kk) === $kk->no_kk ? 'selected' : '' }}>
                                {{ $kk->no_kk }} (Alamat: {{ $kk->alamat }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-input label="Nama Lengkap" name="nama" placeholder="Masukkan nama sesuai KTP..." required value="{{ old('nama', $penduduk->nama) }}" />

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Gender</option>
                        <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Group 2: Kelahiran & Biometrik -->
        <div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Kelahiran & Informasi Personal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Tempat Lahir" name="tempat_lahir" placeholder="Masukkan kota lahir..." required value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}" />
                <x-input label="Tanggal Lahir" name="tanggal_lahir" type="date" required value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->format('Y-m-d') : '') }}" />

                <div>
                    <label for="agama_id" class="block text-sm font-semibold text-slate-700 mb-2">Agama</label>
                    <select id="agama_id" name="agama_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Agama</option>
                        @foreach ($agamas as $agama)
                            <option value="{{ $agama->id }}" {{ (int)old('agama_id', $penduduk->agama_id) === $agama->id ? 'selected' : '' }}>{{ $agama->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="golongan_darah_id" class="block text-sm font-semibold text-slate-700 mb-2">Golongan Darah</label>
                    <select id="golongan_darah_id" name="golongan_darah_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Golongan</option>
                        @foreach ($goldars as $g)
                            <option value="{{ $g->id }}" {{ (int)old('golongan_darah_id', $penduduk->golongan_darah_id) === $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status_kawin_id" class="block text-sm font-semibold text-slate-700 mb-2">Status Perkawinan</label>
                    <select id="status_kawin_id" name="status_kawin_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Status</option>
                        @foreach ($kawins as $kawin)
                            <option value="{{ $kawin->id }}" {{ (int)old('status_kawin_id', $penduduk->status_kawin_id) === $kawin->id ? 'selected' : '' }}>{{ $kawin->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kewarganegaraan_id" class="block text-sm font-semibold text-slate-700 mb-2">Kewarganegaraan</label>
                    <select id="kewarganegaraan_id" name="kewarganegaraan_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Kewarganegaraan</option>
                        @foreach ($wargas as $w)
                            <option value="{{ $w->id }}" {{ (int)old('kewarganegaraan_id', $penduduk->kewarganegaraan_id) === $w->id ? 'selected' : '' }}>{{ $w->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Group 3: Pendidikan & Pekerjaan -->
        <div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Pendidikan & Pekerjaan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="pendidikan_id" class="block text-sm font-semibold text-slate-700 mb-2">Pendidikan Terakhir</label>
                    <select id="pendidikan_id" name="pendidikan_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Pendidikan</option>
                        @foreach ($pendidikans as $p)
                            <option value="{{ $p->id }}" {{ (int)old('pendidikan_id', $penduduk->pendidikan_id) === $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pekerjaan_id" class="block text-sm font-semibold text-slate-700 mb-2">Pekerjaan</label>
                    <select id="pekerjaan_id" name="pekerjaan_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                        <option value="">Pilih Pekerjaan</option>
                        @foreach ($pekerjaans as $pe)
                            <option value="{{ $pe->id }}" {{ (int)old('pekerjaan_id', $penduduk->pekerjaan_id) === $pe->id ? 'selected' : '' }}>{{ $pe->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Group 4: Alamat & Wilayah -->
        <div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Alamat & Tempat Tinggal</h3>
            <div class="space-y-4">
                <x-input label="Alamat Lengkap" name="alamat" placeholder="Masukkan nama jalan, nomor rumah, dll..." required value="{{ old('alamat', $penduduk->alamat) }}" />
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="dusun_id" class="block text-sm font-semibold text-slate-700 mb-2">Dusun</label>
                        <select id="dusun_id" name="dusun_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($dusuns as $d)
                                <option value="{{ $d->id }}" {{ (int)old('dusun_id', $penduduk->dusun_id) === $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="rw_id" class="block text-sm font-semibold text-slate-700 mb-2">RW</label>
                        <select id="rw_id" name="rw_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($rws as $rw)
                                <option value="{{ $rw->id }}" {{ (int)old('rw_id', $penduduk->rw_id) === $rw->id ? 'selected' : '' }}>RW {{ $rw->nomor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="rt_id" class="block text-sm font-semibold text-slate-700 mb-2">RT</label>
                        <select id="rt_id" name="rt_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}" {{ (int)old('rt_id', $penduduk->rt_id) === $rt->id ? 'selected' : '' }}>RT {{ $rt->nomor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status_tinggal_id" class="block text-sm font-semibold text-slate-700 mb-2">Status Tinggal</label>
                        <select id="status_tinggal_id" name="status_tinggal_id" required class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-600 focus:outline-none sm:text-sm">
                            @foreach ($tinggals as $tinggal)
                                <option value="{{ $tinggal->id }}" {{ (int)old('status_tinggal_id', $penduduk->status_tinggal_id) === $tinggal->id ? 'selected' : '' }}>{{ $tinggal->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Group 5: Kontak & Berkas -->
        <div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Kontak & Berkas Pendukung</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Nomor HP (WhatsApp)" name="nomor_hp" placeholder="Contoh: 08123..." value="{{ old('nomor_hp', $penduduk->nomor_hp) }}" />
                <x-input label="Alamat Email" name="email" type="email" placeholder="Contoh: nama@domain.com" value="{{ old('email', $penduduk->email) }}" />
                
                <div>
                    <label for="foto_file" class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Profil</label>
                    <input type="file" id="foto_file" name="foto_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.master.penduduk.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded bg-indigo-600 text-white hover:bg-indigo-700">Perbarui Data Penduduk</button>
        </div>
    </form>
</x-card>
@endsection
