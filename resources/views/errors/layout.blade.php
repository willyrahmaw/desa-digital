<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    @php
        try {
            $settings = $settings ?? \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            $settings = [];
        }
        $namaDesa = $settings['nama_desa'] ?? 'Desa Candraloka';
        $kecamatan = $settings['kecamatan'] ?? 'Astraguna';
        $kabupaten = $settings['kabupaten'] ?? 'Nirwana Raya';
        $alamatKantor = $settings['alamat_kantor'] ?? 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana';
        $teleponDesa = $settings['telepon_desa'] ?? '+62 811-7788-9900';
        $emailDesa = $settings['email_desa'] ?? 'kontak@candraloka.desa.id';
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pemberitahuan Sistem') — {{ $namaDesa }}</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1E3A8A">

    {{-- Fonts: Plus Jakarta Sans (Civic Standard) & Lora (Editorial Accent) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS Engine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        civic: {
                            50: '#F0F7FF',
                            100: '#E0EFFE',
                            600: '#1D4ED8',
                            800: '#1E40AF',
                            900: '#1E3A8A',
                            950: '#0F172A',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100/80 text-slate-800 min-h-screen flex flex-col justify-between font-sans antialiased selection:bg-blue-800 selection:text-white">

    {{-- Top Institutional Red-White Accent Line --}}
    <div class="h-1.5 w-full bg-gradient-to-r from-red-600 via-white to-blue-800"></div>

    {{-- Official Government Header --}}
    <header class="bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            {{-- Institutional Logo & Entity Description --}}
            <a href="{{ route('public.home') }}" class="flex items-center gap-3.5 group text-center sm:text-left">
                <div class="w-12 h-12 shrink-0 bg-slate-50 border border-slate-200 rounded-xl p-1 flex items-center justify-center shadow-xs">
                    <img src="{{ !empty($settings['logo_desa']) ? asset('storage/' . $settings['logo_desa']) : asset('storage/pengaturan/logo-desa.png') }}" 
                         alt="Lambang Instansi" 
                         class="w-full h-full object-contain"
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Coat_of_arms_of_Indonesia.svg/200px-Coat_of_arms_of_Indonesia.svg.png'">
                </div>
                <div>
                    <div class="text-[10px] sm:text-[11px] font-bold text-slate-500 tracking-wider uppercase">
                        Pemerintah Kabupaten {{ $kabupaten }} &bull; Kecamatan {{ $kecamatan }}
                    </div>
                    <div class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight leading-tight group-hover:text-blue-800 transition-colors">
                        Pemerintah {{ $namaDesa }}
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium">
                        Sistem Informasi & Pelayanan Administrasi Desa Terpadu
                    </div>
                </div>
            </a>

            {{-- Service Status & Portal Link --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-300 rounded-lg transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Portal Utama
                </a>
                <a href="{{ route('public.pengaduan.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Bantuan / Pengaduan
                </a>
            </div>
        </div>
    </header>

    {{-- Main Civic Error Card Container --}}
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-10 sm:py-14 flex items-center justify-center">
        <div class="w-full bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            
            {{-- Official Notice Title Header --}}
            <div class="px-6 sm:px-8 py-4 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                    <div class="w-2.5 h-2.5 rounded-full @yield('indicator_color', 'bg-blue-600')"></div>
                    <span>@yield('status_badge', 'Pemberitahuan Sistem Informasi')</span>
                </div>
                <span class="text-xs font-mono font-bold text-slate-500 bg-white border border-slate-200 px-2.5 py-0.5 rounded">
                    HTTP @yield('code', '404')
                </span>
            </div>

            {{-- Body Section --}}
            <div class="p-6 sm:p-10 space-y-8">
                
                {{-- Main Explanation Block --}}
                <div class="flex flex-col sm:flex-row items-start gap-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-2xl @yield('badge_bg', 'bg-blue-50 border border-blue-200 text-blue-800') flex items-center justify-center shadow-2xs">
                        @yield('icon')
                    </div>
                    <div class="space-y-2.5">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                            @yield('heading', 'Terjadi Kendala pada Permintaan Anda')
                        </h1>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed text-justify sm:text-left">
                            @yield('message', 'Halaman atau dokumen yang Anda tuju tidak dapat diproses oleh peladen saat ini.')
                        </p>
                    </div>
                </div>

                {{-- Suggested Institutional Actions --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 space-y-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Langkah yang Disarankan:
                    </div>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-1.5 list-disc list-inside">
                        @yield('suggestions')
                    </ul>
                </div>

                {{-- Action Buttons Toolbar --}}
                <div class="pt-2 flex flex-wrap items-center gap-3 border-t border-slate-100">
                    <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-900 hover:bg-blue-950 text-white font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Kembali ke Beranda Utama
                    </a>

                    <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('public.home') }}'" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Halaman Sebelumnya
                    </button>

                    @yield('extra_action')
                </div>

                {{-- Institutional Directory / Fast Access --}}
                <div class="pt-4 border-t border-slate-100">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Layanan Informasi & Administrasi Resmi:</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                        <a href="{{ route('public.layanan') }}" class="p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-colors block text-slate-700 hover:text-blue-900 font-medium">
                            &rarr; Pelayanan Surat
                        </a>
                        <a href="{{ route('public.berita.index') }}" class="p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-colors block text-slate-700 hover:text-blue-900 font-medium">
                            &rarr; Kabar & Berita Desa
                        </a>
                        <a href="{{ route('public.home') }}#apbdes" class="p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-colors block text-slate-700 hover:text-blue-900 font-medium">
                            &rarr; Transparansi APBDes
                        </a>
                        <a href="{{ route('public.pengaduan.index') }}" class="p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-colors block text-slate-700 hover:text-blue-900 font-medium">
                            &rarr; Pengaduan Masyarakat
                        </a>
                    </div>
                </div>

            </div>

            {{-- Institutional Helpdesk & Contact Footer Info --}}
            <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Kantor Balai Desa: {{ $alamatKantor }}</span>
                </div>
                <div class="flex items-center gap-4 font-semibold text-slate-700">
                    <span>Telp: {{ $teleponDesa }}</span>
                    <span>&bull;</span>
                    <span>Email: {{ $emailDesa }}</span>
                </div>
            </div>

        </div>
    </main>

    {{-- Official Government Footer --}}
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 space-y-1">
        <p class="font-semibold text-slate-700">Hak Cipta &copy; {{ date('Y') }} Pemerintah {{ $namaDesa }}. Seluruh Hak Dilindungi Undang-Undang.</p>
        <p class="text-[11px] text-slate-400">Portal Resmi Layanan Pemerintahan & Kependudukan Elektronik</p>
    </footer>

</body>
</html>
