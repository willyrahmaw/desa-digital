@extends('layouts.admin')

@section('title', 'Detail Pengaduan ' . ($pengaduan->nomor_tiket ?: '#' . $pengaduan->id))
@section('breadcrumb-item', 'Detail Pengaduan')
@section('page-title', 'Rincian Pengaduan Warga')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- Top Back Navigation --}}
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <a href="{{ route('admin.master.pengaduan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Pengaduan</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-mono">ID #{{ $pengaduan->id }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                {{ in_array(strtolower($pengaduan->status), ['selesai', 'resolved']) ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' :
                   (in_array(strtolower($pengaduan->status), ['proses', 'process']) ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' :
                   (in_array(strtolower($pengaduan->status), ['ditolak', 'rejected']) ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20' :
                   'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20')) }}">
                {{ ucfirst($pengaduan->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Left: Informasi Laporan Warga (7 Cols) --}}
        <div class="lg:col-span-7 space-y-4">
            
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-2xs space-y-4">
                <div class="pb-3 border-b border-slate-100">
                    <span class="text-xs font-mono font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">
                        {{ $pengaduan->nomor_tiket ?: 'TKT-' . str_pad($pengaduan->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <h2 class="text-base font-bold text-slate-900 mt-2">{{ $pengaduan->judul }}</h2>
                </div>

                {{-- Metadata Grid --}}
                <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Kategori</span>
                        <span class="font-semibold text-slate-800">{{ $pengaduan->kategori ?: 'Umum' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Waktu Pengaduan</span>
                        <span class="font-semibold text-slate-800 font-mono text-[11px]">
                            {{ \Carbon\Carbon::parse($pengaduan->created_at)->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    @if($pengaduan->lokasi)
                        <div class="col-span-2">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Lokasi Kejadian</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">
                                {{ $pengaduan->lokasi }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Full Content --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Uraian Lengkap Laporan</label>
                    <div class="p-3.5 rounded-lg bg-white border border-slate-200 text-xs text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                        {{ $pengaduan->isi }}
                    </div>
                </div>

                {{-- Attachment Photo --}}
                @if($pengaduan->lampiran)
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Lampiran Bukti Foto</label>
                        <div class="rounded-lg border border-slate-200 overflow-hidden bg-slate-50 max-w-sm">
                            <a href="{{ asset('storage/' . $pengaduan->lampiran) }}" target="_blank" class="group block">
                                <img src="{{ asset('storage/' . $pengaduan->lampiran) }}" alt="Lampiran Pengaduan" class="w-full h-auto max-h-60 object-cover">
                                <div class="p-1.5 text-center text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 border-t border-slate-200">
                                    Buka Foto Ukuran Penuh &rarr;
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Profil Pelapor --}}
            <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-2xs space-y-3">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100">
                    Identitas Pelapor
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px]">Nama Pelapor</span>
                        <span class="font-bold text-slate-900">{{ $pengaduan->pelapor->nama ?? ($pengaduan->nama ?? 'Warga Anonim') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">NIK</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $pengaduan->pelapor_nik ?: ($pengaduan->nik ?: '-') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Kontak Telepon</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono text-slate-800">{{ $pengaduan->telepon ?: '-' }}</span>
                            @if($pengaduan->telepon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengaduan->telepon) }}" target="_blank" 
                                   class="text-[10px] font-semibold text-emerald-700 hover:underline">
                                    WhatsApp &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Email</span>
                        <span class="text-slate-700">{{ $pengaduan->email ?: '-' }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Form Tanggapan & Status (5 Cols) --}}
        <div class="lg:col-span-5 space-y-4">
            
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-2xs space-y-4">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tanggapan & Status Tindak Lanjut</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Perbarui status laporan dan kirimkan solusi resmi ke warga</p>
                </div>

                <form action="{{ route('admin.master.pengaduan.respond', $pengaduan->id) }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Status Penanganan <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full text-xs font-semibold rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none bg-white">
                            <option value="pending" {{ in_array(strtolower($pengaduan->status), ['pending']) ? 'selected' : '' }}>Menunggu Tindakan (Pending)</option>
                            <option value="process" {{ in_array(strtolower($pengaduan->status), ['proses', 'process']) ? 'selected' : '' }}>Sedang Diproses (Investigasi / Lapangan)</option>
                            <option value="resolved" {{ in_array(strtolower($pengaduan->status), ['selesai', 'resolved']) ? 'selected' : '' }}>Selesai (Permasalahan Teratasi)</option>
                            <option value="rejected" {{ in_array(strtolower($pengaduan->status), ['ditolak', 'rejected']) ? 'selected' : '' }}>Ditolak (Tidak Sesuai)</option>
                        </select>
                    </div>

                    <div>
                        <label for="balasan" class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Solusi Resmi</label>
                        <textarea id="balasan" name="balasan" rows="4" placeholder="Tuliskan keterangan penanganan atau solusi dari pemerintah desa..."
                                  class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none leading-relaxed">{{ old('balasan', $pengaduan->balasan ?: $pengaduan->tanggapan) }}</textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Tanggapan</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
