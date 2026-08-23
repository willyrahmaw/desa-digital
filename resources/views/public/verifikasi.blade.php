<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Dokumen Resmi — Sistem Administrasi Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased text-slate-900">

    <div class="max-w-lg w-full bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden space-y-0">
        
        {{-- Official Seal Header --}}
        <div class="px-6 py-8 text-center bg-[#0B132B] text-white space-y-2 border-b border-slate-800">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-700/80 border border-blue-400/30 flex items-center justify-center text-amber-300 shadow-md">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 4 4.5.5-3.25 3.25.75 4.5-4-2.25-4 2.25.75-4.5-3.25-3.25 4.5-.5z" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <h1 class="text-base font-extrabold tracking-wide uppercase">Pemerintah Desa</h1>
                <p class="text-[11px] text-blue-300 font-semibold tracking-widest uppercase">Sertifikat Verifikasi Dokumen Elektronik</p>
            </div>
        </div>

        {{-- Verification Result --}}
        <div class="p-6 sm:p-8 space-y-6">
            @if ($isValid)
                {{-- Green Verification Success --}}
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0114 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-extrabold text-emerald-800 uppercase tracking-wide">Dokumen Sah Terverifikasi</h2>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm mx-auto">Dokumen ini diterbitkan secara sah dan tercatat resmi dalam basis data sistem informasi administrasi desa.</p>
                </div>

                {{-- Document Details --}}
                <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50 space-y-3 text-xs">
                    <div class="flex justify-between border-b border-slate-200 pb-2.5">
                        <span class="text-slate-500 font-semibold">Nomor Surat:</span>
                        <span class="text-slate-900 font-bold font-mono">{{ $surat->no_surat }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2.5">
                        <span class="text-slate-500 font-semibold">Jenis Surat:</span>
                        <span class="text-slate-900 font-bold">{{ $surat->templateSurat->nama ?? 'Surat Keterangan Desa' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2.5">
                        <span class="text-slate-500 font-semibold">Nama Pemohon:</span>
                        <span class="text-slate-900 font-bold">{{ $surat->penduduk->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2.5">
                        <span class="text-slate-500 font-semibold">NIK Pemohon:</span>
                        <span class="text-slate-900 font-bold font-mono">{{ $surat->penduduk_nik }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2.5">
                        <span class="text-slate-500 font-semibold">Tanggal Persetujuan:</span>
                        <span class="text-slate-900 font-bold">{{ date('d F Y', strtotime($surat->tanggal_persetujuan ?? $surat->created_at)) }}</span>
                    </div>
                    <div class="flex justify-between items-start pt-0.5">
                        <span class="text-slate-500 font-semibold">Penandatangan:</span>
                        <div class="text-right">
                            <span class="text-slate-900 font-bold block">{{ $surat->ttdOlehPerangkat->nama ?? 'Kepala Desa' }}</span>
                            <span class="text-[11px] text-slate-500 font-medium">{{ $surat->ttdOlehPerangkat->jabatan->nama ?? 'Kepala Desa' }}</span>
                        </div>
                    </div>
                </div>

            @else
                {{-- Red Verification Failed --}}
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-extrabold text-rose-800 uppercase tracking-wide">Verifikasi Tidak Ditemukan</h2>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm mx-auto">Dokumen tidak terdaftar di sistem atau kode unik verifikasi tidak valid. Harap periksa kembali berkas fisik Anda.</p>
                </div>
            @endif

            {{-- Navigation Action --}}
            <div class="pt-2 text-center space-y-3">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Beranda Portal Desa</span>
                </a>
                <div class="text-[11px] text-slate-400">
                    &copy; {{ date('Y') }} Sistem Informasi Pelayanan Administrasi Desa.
                </div>
            </div>

        </div>

    </div>

</body>
</html>
