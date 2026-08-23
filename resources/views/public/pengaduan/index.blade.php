@extends('layouts.public')

@section('title', 'Layanan Pengaduan & Aspirasi Warga (NIK) — ' . ($settings['nama_desa'] ?? 'Desa Digital'))
@section('meta_description', 'Portal pengaduan, pelaporan kendala infrastruktur, dan aspirasi warga Pemerintah ' . ($settings['nama_desa'] ?? 'Desa Digital') . ' berbasis verifikasi NIK.')

@section('content')

{{-- ── 1. PAGE HEADER ──────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Layanan Pengaduan</span>
        </div>
        <div class="pt-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">PORTAL ADUAN WARGA</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Layanan Pengaduan & Aspirasi Warga</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Saluran penyampaian laporan kerusakan sarana publik, kendala administrasi, atau usulan pembangunan berbasis verifikasi NIK.</p>
        </div>
    </div>
</section>

{{-- ── 2. MAIN INTERACTION CONTAINER ───────────────────────────────────── --}}
<section x-data="{ 
    activeTab: 'form',
    nikInput: '',
    isValidatingNik: false,
    nikValid: false,
    nikError: '',
    nama: '',
    alamat: '',
    rt: '',
    rw: '',
    dusun: '',

    checkNik() {
        if (this.nikInput.length !== 16) {
            this.nikError = 'Nomor Induk Kependudukan (NIK) harus berjumlah 16 digit angka.';
            this.nikValid = false;
            return;
        }
        this.isValidatingNik = true;
        this.nikError = '';

        fetch('{{ route('public.pengaduan.check_nik') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nik: this.nikInput })
        })
        .then(res => res.json())
        .then(data => {
            this.isValidatingNik = false;
            if (data.success) {
                this.nikValid = true;
                this.nama = data.data.nama;
                this.alamat = data.data.alamat;
                this.rt = data.data.rt;
                this.rw = data.data.rw;
                this.dusun = data.data.dusun;
                this.nikError = '';
            } else {
                this.nikValid = false;
                this.nikError = data.message;
            }
        })
        .catch(err => {
            this.isValidatingNik = false;
            this.nikValid = false;
            this.nikError = 'Terjadi gangguan jaringan saat memverifikasi NIK.';
        });
    }
}" class="py-14 bg-[#F8FAFC]">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Flash Success Ticket Modal/Banner --}}
        @if(session('success_ticket'))
            <div class="bg-emerald-50 border border-emerald-300 p-6 sm:p-8 rounded-3xl space-y-4 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-emerald-950">Laporan Pengaduan Berhasil Dikirim</h3>
                        <p class="text-xs text-emerald-800 mt-0.5">{{ session('success_ticket')['message'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-emerald-200 text-center space-y-1.5 shadow-xs">
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Nomor Registrasi Tiket Pengaduan Anda</span>
                    <span class="text-2xl sm:text-3xl font-black text-blue-700 font-mono block tracking-wider">{{ session('success_ticket')['ticket'] }}</span>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Harap simpan Nomor Tiket dan NIK Anda untuk memantau status verifikasi dan tindak lanjut dari aparatur desa.</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl text-xs font-bold text-rose-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tab Buttons --}}
        <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
            <button x-on:click="activeTab = 'form'" 
                    :class="activeTab === 'form' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-5 py-2.5 rounded-xl text-xs transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Buat Pengaduan Baru</span>
            </button>
            <button x-on:click="activeTab = 'track'" 
                    :class="activeTab === 'track' ? 'bg-blue-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-5 py-2.5 rounded-xl text-xs transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Cek Status Tindak Lanjut Tiket</span>
            </button>
        </div>

        {{-- ── TAB 1: FORM PENGADUAN NIK ─────────────────────────────────────── --}}
        <div x-show="activeTab === 'form'" class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6">
            
            <div class="pb-4 border-b border-slate-100 space-y-1">
                <h3 class="text-lg font-extrabold text-slate-900">Formulir Laporan / Aspirasi Warga</h3>
                <p class="text-xs text-slate-500">Sistem memverifikasi NIK pemohon secara otomatis ke database kependudukan desa sebelum laporan diproses.</p>
            </div>

            <form action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- 1. NIK Input with Live Validation --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">
                        1. Nomor Induk Kependudukan (NIK 16 Digit) <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="nik" maxlength="16" x-model="nikInput" x-on:input="checkNik()" required
                               placeholder="Contoh: 3515012345670001"
                               class="flex-grow rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-mono font-bold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                        <button type="button" x-on:click="checkNik()" class="px-5 py-2.5 bg-blue-700 text-white text-xs font-bold rounded-xl hover:bg-blue-800 transition-colors shrink-0 shadow-xs">
                            <span x-show="!isValidatingNik">Verifikasi NIK</span>
                            <span x-show="isValidatingNik" style="display: none;">Memeriksa...</span>
                        </button>
                    </div>

                    {{-- Validation Result Messages --}}
                    <div x-show="nikError" class="text-xs font-bold text-rose-700 bg-rose-50 p-3 rounded-xl border border-rose-200 flex items-center gap-2" style="display: none;">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="nikError"></span>
                    </div>

                    <div x-show="nikValid" class="text-xs font-bold text-emerald-800 bg-emerald-50 p-3.5 rounded-xl border border-emerald-200 flex items-center gap-2" style="display: none;">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>NIK Terverifikasi Sah atas nama: <strong x-text="nama"></strong></span>
                    </div>
                </div>

                {{-- 2. Data Otomatis Penduduk (ReadOnly) --}}
                <div x-show="nikValid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-200" style="display: none;">
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Lengkap Warga</span>
                        <span class="text-xs font-extrabold text-slate-900 block mt-0.5" x-text="nama"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Wilayah Dusun</span>
                        <span class="text-xs font-extrabold text-slate-900 block mt-0.5" x-text="dusun"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">RT / RW</span>
                        <span class="text-xs font-extrabold text-slate-900 block mt-0.5">RT <span x-text="rt"></span> / RW <span x-text="rw"></span></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Alamat Domisili Terdaftar</span>
                        <span class="text-xs font-semibold text-slate-800 block mt-0.5" x-text="alamat"></span>
                    </div>
                </div>

                {{-- 3. Kontak Pelapor --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="telepon" required placeholder="081234567890" 
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Email Pelapor (Opsional)
                        </label>
                        <input type="email" name="email" placeholder="nama@email.com" 
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                    </div>
                </div>

                {{-- 4. Kategori & Rincian Pengaduan --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Kategori Pengaduan <span class="text-rose-500">*</span>
                        </label>
                        <select name="kategori" required class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                            <option value="Infrastruktur & Jalan">Infrastruktur & Sarana Jalan Rusak</option>
                            <option value="Pelayanan Administrasi">Pelayanan Administrasi & Loket Kantor Desa</option>
                            <option value="Bantuan Sosial">Bantuan Sosial & Kesejahteraan Warga</option>
                            <option value="Keamanan & Ketertiban">Keamanan & Ketertiban Lingkungan</option>
                            <option value="Kebersihan & Sampah">Kebersihan & Pengelolaan Sampah Lingkungan</option>
                            <option value="Lainnya">Aspirasi / Usulan Pembangunan Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Judul Pengaduan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="judul" required placeholder="Ringkasan singkat masalah atau aspirasi..." 
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-bold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Rincian Laporan Pengaduan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="isi" rows="4" required placeholder="Jelaskan kronologi, detail kerusakan, atau saran perbaikan secara jelas..." 
                                  class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            Lokasi Kejadian
                        </label>
                        <input type="text" name="lokasi" placeholder="Misal: Jalan Utama Dusun A dekat Pos Ronda" 
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                    </div>
                </div>

                {{-- 5. Upload Bukti Foto --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Upload Bukti Foto (Maksimal 5 Foto, Max 10MB) <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="foto[]" multiple accept="image/jpeg,image/png,image/webp" required
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-500">Format yang didukung: JPG, PNG, WEBP. Maksimal 5 file pendukung.</p>
                </div>

                {{-- Submit Action --}}
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" :disabled="!nikValid" 
                            :class="nikValid ? 'bg-blue-700 hover:bg-blue-800 cursor-pointer shadow-md' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3.5 px-6 text-xs font-bold text-white rounded-xl transition-all text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Kirim Laporan Pengaduan Resmi</span>
                    </button>
                </div>

            </form>
        </div>

        {{-- ── TAB 2: TRACKING STATUS PENGADUAN ──────────────────────────────── --}}
        <div x-show="activeTab === 'track'" class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6" style="display: none;">
            
            <div class="pb-4 border-b border-slate-100 space-y-1">
                <h3 class="text-lg font-extrabold text-slate-900">Pelacakan Status Tindak Lanjut Tiket</h3>
                <p class="text-xs text-slate-500">Masukkan Nomor Registrasi Tiket dan NIK pelapor untuk memantau proses verifikasi.</p>
            </div>

            @if(session('error_track'))
                <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl text-xs font-bold text-rose-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error_track') }}</span>
                </div>
            @endif

            <form action="{{ route('public.pengaduan.track') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">Nomor Tiket Pengaduan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nomor_tiket" required placeholder="Contoh: PGD-2026-000123" 
                           class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-mono font-bold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">NIK Pelapor (16 Digit) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nik" maxlength="16" required placeholder="Masukkan NIK 16 digit" 
                           class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-mono font-bold focus:border-blue-700 focus:ring-1 focus:ring-blue-700 focus:outline-none">
                </div>

                <button type="submit" class="w-full py-3 px-6 bg-blue-700 text-white text-xs font-bold rounded-xl hover:bg-blue-800 transition-colors shadow-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Periksa Status Pengaduan</span>
                </button>
            </form>

            {{-- Tracked Result Display --}}
            @if(session('tracked_data'))
                @php $track = session('tracked_data'); @endphp
                <div class="mt-6 bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nomor Registrasi Tiket</span>
                            <span class="text-lg font-black text-blue-700 font-mono">{{ $track->nomor_tiket }}</span>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $track->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            Status: {{ $track->status }}
                        </span>
                    </div>

                    <div class="space-y-2 text-xs text-slate-700">
                        <p><strong>Judul Laporan:</strong> {{ $track->judul }}</p>
                        <p><strong>Tanggal Masuk:</strong> {{ \Carbon\Carbon::parse($track->created_at)->translatedFormat('d F Y H:i') }} WIB</p>
                        <p class="leading-relaxed"><strong>Isi Laporan:</strong> {{ $track->isi }}</p>
                    </div>

                    @if($track->balasan)
                        <div class="bg-white p-4 rounded-xl border border-blue-200 space-y-1.5">
                            <span class="text-xs font-bold text-blue-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                <span>Tindak Lanjut & Balasan Resmi Aparatur Desa:</span>
                            </span>
                            <p class="text-xs text-slate-800 leading-relaxed">{{ $track->balasan }}</p>
                        </div>
                    @else
                        <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-xs text-blue-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Laporan Anda telah tercatat dan sedang dalam tahap verifikasi oleh tim teknis desa.</span>
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>
</section>

@endsection
