@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Identitas Desa')
@section('breadcrumb-item', 'Pengaturan')
@section('page-title', 'Pengaturan Profil, Logo & Sistem Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-xs font-semibold flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-xs font-semibold space-y-1 shadow-2xs">
            <div class="font-bold">Terjadi kesalahan validasi:</div>
            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden">
        
        <div class="p-5 border-b border-slate-200 bg-slate-50/50">
            <h2 class="text-sm font-bold text-slate-900">
                Konfigurasi Portal & Identitas Resmi Desa
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Unggah logo resmi, lambang instansi, icon favicon browser, serta informasi profil desa di bawah ini.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            {{-- ── GROUP 1: LOGO & ICON DESA (IDENTITAS GRAFIS) ───────────── --}}
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                    <div class="p-1 rounded bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Logo & Favicon Resmi Desa</h3>
                        <p class="text-[11px] text-slate-500">Logo digunakan pada Kop Surat, Header Website Publik, Sidebar Admin, dan Surat Cetak.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Upload 1: Logo Resmi Desa --}}
                    <div x-data="{
                        preview: '{{ !empty($settings['logo_desa']) ? asset('storage/' . $settings['logo_desa']) : '' }}',
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.preview = URL.createObjectURL(file);
                            }
                        }
                    }" class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 space-y-3">
                        <label class="block text-xs font-bold text-slate-700">
                            Logo / Lambang Resmi Desa <span class="text-rose-500">*</span>
                        </label>

                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg bg-white border border-slate-200 shadow-2xs flex items-center justify-center p-2 shrink-0 overflow-hidden">
                                <template x-if="preview">
                                    <img :src="preview" alt="Preview Logo" class="max-w-full max-h-full object-contain">
                                </template>
                                <template x-if="!preview">
                                    <div class="text-center text-slate-400">
                                        <svg class="w-8 h-8 mx-auto stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[9px] block font-semibold">Belum Ada</span>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-1.5 flex-1">
                                <input type="file" name="logo_desa" id="logo_desa" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                                       @change="handleFile($event)"
                                       class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 cursor-pointer">
                                <p class="text-[10px] text-slate-400 leading-tight">Format: PNG Transparan, SVG, WEBP, atau JPG (Maks. 3 MB).</p>
                            </div>
                        </div>
                    </div>

                    {{-- Upload 2: Icon / Favicon Browser --}}
                    <div x-data="{
                        preview: '{{ !empty($settings['favicon']) ? asset('storage/' . $settings['favicon']) : (!empty($settings['icon_desa']) ? asset('storage/' . $settings['icon_desa']) : '') }}',
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.preview = URL.createObjectURL(file);
                            }
                        }
                    }" class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 space-y-3">
                        <label class="block text-xs font-bold text-slate-700">
                            Icon / Favicon Browser
                        </label>

                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg bg-white border border-slate-200 shadow-2xs flex items-center justify-center p-2 shrink-0 overflow-hidden">
                                <template x-if="preview">
                                    <img :src="preview" alt="Preview Favicon" class="w-10 h-10 object-contain">
                                </template>
                                <template x-if="!preview">
                                    <div class="text-center text-slate-400">
                                        <svg class="w-8 h-8 mx-auto stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="text-[9px] block font-semibold">Favicon</span>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-1.5 flex-1">
                                <input type="file" name="favicon" id="favicon" accept="image/x-icon,image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                                       @change="handleFile($event)"
                                       class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 cursor-pointer">
                                <p class="text-[10px] text-slate-400 leading-tight">Ikon tab peramban. Format: ICO, PNG, SVG (Rasio 1:1, Maks. 2 MB).</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── GROUP 2: IDENTITAS WILAYAH & KANTOR ───────────────────── --}}
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                    <div class="p-1 rounded bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Identitas Wilayah & Balai Desa</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemerintahan Desa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_desa" required value="{{ old('nama_desa', $settings['nama_desa'] ?? 'Desa Candraloka') }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="kecamatan" required value="{{ old('kecamatan', $settings['kecamatan'] ?? ($settings['nama_kecamatan'] ?? 'Astraguna')) }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                        <input type="text" name="kabupaten" required value="{{ old('kabupaten', $settings['kabupaten'] ?? ($settings['nama_kabupaten'] ?? 'Nirwana Raya')) }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $settings['kode_pos'] ?? '99881') }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Kantor Balai Desa <span class="text-rose-500">*</span></label>
                        <input type="text" name="alamat_kantor" required value="{{ old('alamat_kantor', $settings['alamat_kantor'] ?? 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana') }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Motto / Slogan Desa</label>
                        <input type="text" name="motto_desa" value="{{ old('motto_desa', $settings['motto_desa'] ?? 'Harmoni Alam, Cahaya Kemakmuran, dan Kearifan Bersama') }}"
                               class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- ── GROUP 3: KONTAK RESMI & STRUKTUR PEMIMPIN ─────────────── --}}
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                    <div class="p-1 rounded bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kontak Resmi & Pimpinan Eksekutif</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Resmi Desa</label>
                        <input type="email" name="email_desa" value="{{ old('email_desa', $settings['email_desa'] ?? 'kontak@candraloka.desa.id') }}"
                               class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / Hotline</label>
                        <input type="text" name="telp_desa" value="{{ old('telp_desa', $settings['telp_desa'] ?? ($settings['telepon_desa'] ?? '+62 811-7788-9900')) }}"
                               class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kepala Desa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kades" required value="{{ old('nama_kades', $settings['nama_kades'] ?? 'Ki Ageng Suryakencana, S.Sos') }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sekretaris Desa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sekdes" required value="{{ old('nama_sekdes', $settings['nama_sekdes'] ?? 'Damar Prameswara, S.Kom') }}"
                               class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- ── GROUP 4: VISI & MISI DESA ──────────────────────────────── --}}
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                    <div class="p-1 rounded bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Visi & Misi Pemerintahan Desa</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Visi Desa</label>
                        <textarea name="visi" rows="2" class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">{{ old('visi', $settings['visi'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Misi Desa (Pisahkan dengan baris baru)</label>
                        <textarea name="misi" rows="4" class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">{{ old('misi', $settings['misi'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end pt-4 border-t border-slate-200">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2 text-xs font-bold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
            
        </form>
    </div>

</div>
@endsection
