<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $surat->templateSurat->nama ?? 'Surat Resmi' }} - {{ $surat->no_surat }}</title>
    
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, "Liberation Serif", serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Screen View: Centered A4 Sheet */
        .page-container {
            width: 210mm;
            min-height: 297mm;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20mm 20mm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Kop Surat Header */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 12px;
            border-bottom: 4px double #000000;
            margin-bottom: 24px;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .kop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            line-height: 1.25;
        }

        .kop-line-1 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .kop-line-2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
        }

        .kop-line-3 {
            font-size: 15pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 2px 0 0 0;
        }

        .kop-address {
            font-size: 9pt;
            font-style: italic;
            margin: 4px 0 0 0;
            color: #1e293b;
        }

        .kop-contact {
            font-size: 8.5pt;
            margin: 2px 0 0 0;
            color: #334155;
        }

        /* Document Typography */
        .document-body {
            text-align: justify;
        }

        .document-body p {
            margin: 0 0 14px 0;
        }

        .document-body table {
            border-collapse: collapse;
        }

        /* Floating Action Bar on Screen */
        .screen-toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            padding: 8px 14px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .screen-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;
            font-size: 12px;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .btn-print {
            background-color: #4f46e5;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #4338ca;
        }

        .btn-back {
            background-color: #334155;
            color: #e2e8f0;
        }
        .btn-back:hover {
            background-color: #475569;
            color: #ffffff;
        }

        /* Print Media Styles */
        @media print {
            body {
                background-color: #ffffff;
            }

            .screen-toolbar {
                display: none !important;
            }

            .page-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 15mm 20mm;
                box-shadow: none;
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Top Bar (Hidden on Print) -->
    <div class="screen-toolbar">
        <a href="{{ route('admin.master.surat.index') }}" class="screen-btn btn-back">
            <span>&larr; Kembali</span>
        </a>
        <button onclick="window.print()" class="screen-btn btn-print">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Surat (A4)</span>
        </button>
    </div>

    <!-- Official A4 Document Paper Sheet -->
    <div class="page-container">
        
        <!-- Official Village Letterhead (Kop Surat) -->
        @if(!empty($surat->templateSurat->dengan_kop))
            <div class="kop-surat">
                <div class="kop-logo">
                    <img src="{{ !empty($settings['logo_desa']) ? asset('storage/' . $settings['logo_desa']) : asset('storage/pengaturan/logo-desa.png') }}" 
                         alt="Logo Desa"
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Coat_of_arms_of_Indonesia.svg/200px-Coat_of_arms_of_Indonesia.svg.png'">
                </div>
                <div class="kop-text">
                    <h4 class="kop-line-1">{{ $settings['kop_line_1'] ?? ($surat->templateSurat->kop_line_1 ?? ('PEMERINTAH KABUPATEN ' . strtoupper($settings['kabupaten'] ?? 'NIRWANA RAYA'))) }}</h4>
                    <h3 class="kop-line-2">{{ $settings['kop_line_2'] ?? ($surat->templateSurat->kop_line_2 ?? ('KECAMATAN ' . strtoupper($settings['kecamatan'] ?? 'ASTRAGUNA'))) }}</h3>
                    <h2 class="kop-line-3">{{ $settings['kop_line_3'] ?? ($surat->templateSurat->kop_line_3 ?? ('PEMERINTAH ' . strtoupper($settings['nama_desa'] ?? 'DESA CANDRALOKA'))) }}</h2>
                    <p class="kop-address">{{ $settings['alamat_kantor'] ?? ($surat->templateSurat->kop_alamat ?? 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya 99881') }}</p>
                    <p class="kop-contact">Website: {{ url('/') }} &nbsp;|&nbsp; Email: {{ $settings['email_desa'] ?? 'kontak@candraloka.desa.id' }} &nbsp;|&nbsp; Telp: {{ $settings['telepon_desa'] ?? '+62 811-7788-9900' }}</p>
                </div>
            </div>
        @endif

        <!-- Document Body Content (True WYSIWYG HTML) -->
        <div class="document-body">
            {!! $content !!}
        </div>

    </div>

</body>
</html>
