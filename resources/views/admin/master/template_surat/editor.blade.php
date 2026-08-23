@extends('layouts.admin')

@section('title', isset($template) ? 'Edit Template Surat: ' . $template->nama : 'Buat Template Surat Baru')
@section('breadcrumb-item', 'Template Surat')
@section('page-title', isset($template) ? 'Edit Template Surat' : 'Buat Template Surat Baru')

@php
    try {
        $settings = $settings ?? \App\Models\Pengaturan::pluck('value', 'key')->toArray();
    } catch(\Throwable $e) {
        $settings = [];
    }
    $defaultKopLine1 = !empty($settings['kop_line_1']) ? $settings['kop_line_1'] : 'PEMERINTAH KABUPATEN ' . strtoupper($settings['nama_kabupaten'] ?? ($settings['kabupaten'] ?? 'NIRWANA RAYA'));
    $defaultKopLine2 = !empty($settings['kop_line_2']) ? $settings['kop_line_2'] : 'KECAMATAN ' . strtoupper($settings['nama_kecamatan'] ?? ($settings['kecamatan'] ?? 'ASTRAGUNA'));
    $defaultKopLine3 = !empty($settings['kop_line_3']) ? $settings['kop_line_3'] : 'PEMERINTAH ' . strtoupper($settings['nama_desa'] ?? 'DESA CANDRALOKA');
    $defaultKopAlamat = !empty($settings['kop_alamat']) ? $settings['kop_alamat'] : ($settings['alamat_kantor'] ?? 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya 99881');
    $defaultKopKontak = !empty($settings['kop_kontak']) ? $settings['kop_kontak'] : 'Website: https://candraloka.desa.id | Email: ' . ($settings['email_desa'] ?? 'kontak@candraloka.desa.id') . ' | Telp: ' . ($settings['telepon_desa'] ?? '+62 811-7788-9900');

    // Prepare initial HTML content for Word editor
    $rawContent = $template->canvas_json ?? ($template->content ?? '');
    $initialHtml = '';

    if (str_starts_with(trim($rawContent), '{') && str_contains($rawContent, '"objects"')) {
        $json = json_decode($rawContent, true);
        if (!empty($json['objects'])) {
            foreach ($json['objects'] as $obj) {
                if (isset($obj['text'])) {
                    $align = $obj['textAlign'] ?? 'left';
                    $weight = ($obj['fontWeight'] ?? '') === 'bold' ? 'font-weight:bold;' : '';
                    $initialHtml .= "<p style=\"text-align:{$align};{$weight} margin-bottom: 1em;\">" . nl2br(e($obj['text'])) . "</p>";
                }
            }
        }
    } else {
        $initialHtml = $rawContent;
    }

    if (empty(trim($initialHtml))) {
        // Default SKTM template
        $initialHtml = <<<HTML
<div style="text-align: center; margin-bottom: 24px;">
    <h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN TIDAK MAMPU</h3>
    <p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>
</div>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:
</p>

<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;">
    <tbody>
        <tr>
            <td style="width: 190px; padding: 3px 0;">Nama Lengkap</td>
            <td style="width: 15px; padding: 3px 0;">:</td>
            <td style="padding: 3px 0; font-weight: bold;">[NAMA]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">NIK</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0; font-weight: bold;">[NIK]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Nomor KK</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[NO_KK]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Tempat, Tanggal Lahir</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Jenis Kelamin</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[JENIS_KELAMIN]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Agama</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[AGAMA]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Pekerjaan</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[PEKERJAAN]</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Alamat Domisili</td>
            <td style="padding: 3px 0;">:</td>
            <td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td>
        </tr>
    </tbody>
</table>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Berdasarkan pendataan dan pengamatan di lapangan, yang bersangkutan adalah benar-benar penduduk Desa [NAMA_DESA] yang tergolong dalam keluarga prasejahtera / tidak mampu secara ekonomi. Surat keterangan ini diterbitkan untuk dipergunakan sebagai persyaratan: <strong>[KEPERLUAN]</strong>.
</p>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">
    Demikian surat keterangan tidak mampu ini dibuat dengan sebenarnya dan penuh tanggung jawab untuk dapat dipergunakan sebagaimana mestinya.
</p>

<table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
    <tbody>
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p>
                <p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p>
                <p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p>
                <p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p>
            </td>
        </tr>
    </tbody>
</table>
HTML;
    }

    $editorConfig = [
        'nama' => $template->nama ?? 'Surat Keterangan Tidak Mampu',
        'kategoriSurat' => $template->kategori_surat ?? 'SKTM',
        'formatNomorSurat' => $template->format_nomor_surat ?? '[NOMOR]/SKTM/VII/[TAHUN]',
        'denganKop' => isset($template) ? (bool)$template->dengan_kop : true,
        'statusAktif' => isset($template) ? (bool)$template->status_aktif : true,
        'kopLine1' => $template->kop_line_1 ?? $defaultKopLine1,
        'kopLine2' => $template->kop_line_2 ?? $defaultKopLine2,
        'kopLine3' => $template->kop_line_3 ?? $defaultKopLine3,
        'kopAlamat' => $template->kop_alamat ?? $defaultKopAlamat,
        'kopKontak' => $template->kop_kontak ?? $defaultKopKontak,
        'defaultKopLine1' => $defaultKopLine1,
        'defaultKopLine2' => $defaultKopLine2,
        'defaultKopLine3' => $defaultKopLine3,
        'defaultKopAlamat' => $defaultKopAlamat,
        'defaultKopKontak' => $defaultKopKontak,
    ];
@endphp

@section('content')
<div x-data="wordLetterEditor(@js($editorConfig))" 
     x-init="initEditor()" 
     :class="{ 'fixed inset-0 z-50 bg-slate-950/95 p-4 sm:p-6 overflow-y-auto': isFullscreen }"
     class="space-y-4 transition-all w-full min-w-0 max-w-full overflow-hidden">

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-2xs w-full min-w-0">
        <div class="flex items-center gap-2.5 min-w-0">
            <a href="{{ route('admin.master.template_surat.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition-colors shrink-0">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Daftar Template</span>
            </a>
            <span class="text-slate-300 hidden sm:inline">|</span>
            <span class="text-xs font-medium text-slate-500 truncate hidden sm:inline">
                Standar Tata Naskah Dinas Kertas A4 (Permendagri)
            </span>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <!-- Fullscreen Mode Toggle -->
            <button type="button" @click="isFullscreen = !isFullscreen" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition-colors cursor-pointer" :title="isFullscreen ? 'Keluar Layar Penuh' : 'Mode Layar Penuh'">
                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!isFullscreen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    <path x-show="isFullscreen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="hidden md:inline" x-text="isFullscreen ? 'Tutup Fokus' : 'Layar Penuh'"></span>
            </button>

            <!-- Zoom Controls -->
            <div class="hidden sm:flex items-center bg-slate-100 rounded-lg p-0.5 text-xs border border-slate-300">
                <button type="button" @click="zoomOut()" class="px-2 py-1 text-slate-700 hover:text-slate-900 font-bold hover:bg-slate-200 rounded transition-colors cursor-pointer" title="Perkecil Kanvas">-</button>
                <span class="px-2 font-mono text-[11px] text-slate-800 font-bold" x-text="zoomLevel + '%'">100%</span>
                <button type="button" @click="zoomIn()" class="px-2 py-1 text-slate-700 hover:text-slate-900 font-bold hover:bg-slate-200 rounded transition-colors cursor-pointer" title="Perbesar Kanvas">+</button>
            </div>

            <!-- Print Preview Button -->
            <button type="button" @click="openPreview()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Pratinjau</span>
            </button>

            <!-- Save Form Trigger Button -->
            <button type="button" @click="submitTemplateForm()" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>Simpan Template</span>
            </button>
        </div>
    </div>

    <!-- Dual-Pane Layout: Editor Workspace + Right Settings Panel -->
    <div class="flex flex-col lg:flex-row gap-6 items-start w-full min-w-0 max-w-full">

        <!-- Left / Center: Editor Ribbon + Paper Canvas (Flexible width) -->
        <div class="flex-1 w-full min-w-0 space-y-3">

            <!-- Word-Style Executive Ribbon Toolbar -->
            <div class="editor-toolbar bg-white border border-slate-300 rounded-xl shadow-xs overflow-hidden sticky top-16 z-20 w-full min-w-0">
                
                <!-- Ribbon Row 1: Presets & Variables (Institutional Header) -->
                <div class="bg-slate-100/80 border-b border-slate-200 px-3.5 py-2 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <!-- Preset Templates Loader -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-slate-700 flex items-center gap-1.5 shrink-0 text-xs">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Format Naskah Baku:</span>
                        </span>
                        <select @change="loadPreset($event.target.value); $event.target.value='';" data-no-search="true" class="text-xs bg-white border border-slate-300 rounded-lg px-2.5 py-1 text-slate-800 font-semibold focus:border-blue-600 focus:outline-none shadow-2xs no-search no-select2">
                            <option value="">-- Muat Format Surat Standar --</option>
                            <option value="sktm">Surat Keterangan Tidak Mampu (SKTM)</option>
                            <option value="sku">Surat Keterangan Usaha (SKU)</option>
                            <option value="skd">Surat Keterangan Domisili (SKD)</option>
                            <option value="skck">Surat Pengantar Kelakuan Baik (SKCK)</option>
                            <option value="belum_menikah">Surat Keterangan Belum Menikah</option>
                            <option value="kematian">Surat Keterangan Kematian</option>
                            <option value="kelahiran">Surat Keterangan Kelahiran</option>
                            <option value="penghasilan">Surat Keterangan Penghasilan</option>
                        </select>
                    </div>

                    <!-- Insert Variables Quick Menu -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-slate-700 shrink-0 text-xs">Variabel Kependudukan:</span>
                        <select @change="if($event.target.value) { insertVariable($event.target.value); $event.target.value=''; }" data-no-search="true" class="text-xs bg-white border border-slate-300 text-slate-800 font-semibold rounded-lg px-2.5 py-1 focus:outline-none focus:border-blue-600 no-search no-select2">
                            <option value="">+ Sisipkan Parameter Data...</option>
                            <optgroup label="Nomor & Keperluan">
                                <option value="[NOMOR_SURAT]">Nomor Surat ([NOMOR_SURAT])</option>
                                <option value="[KEPERLUAN]">Keperluan Surat ([KEPERLUAN])</option>
                                <option value="[TANGGAL_SURAT]">Tanggal Surat ([TANGGAL_SURAT])</option>
                            </optgroup>
                            <optgroup label="Data Pemohon / Warga">
                                <option value="[NAMA]">Nama Lengkap ([NAMA])</option>
                                <option value="[NIK]">NIK ([NIK])</option>
                                <option value="[NO_KK]">Nomor KK ([NO_KK])</option>
                                <option value="[TEMPAT_TANGGAL_LAHIR]">Tempat, Tgl Lahir ([TEMPAT_TANGGAL_LAHIR])</option>
                                <option value="[JENIS_KELAMIN]">Jenis Kelamin ([JENIS_KELAMIN])</option>
                                <option value="[AGAMA]">Agama ([AGAMA])</option>
                                <option value="[PEKERJAAN]">Pekerjaan ([PEKERJAAN])</option>
                                <option value="[STATUS_KAWIN]">Status Kawin ([STATUS_KAWIN])</option>
                                <option value="[ALAMAT]">Alamat Lengkap ([ALAMAT])</option>
                                <option value="[RT]">RT ([RT])</option>
                                <option value="[RW]">RW ([RW])</option>
                                <option value="[DUSUN]">Dusun ([DUSUN])</option>
                            </optgroup>
                            <optgroup label="Pejabat Penandatangan">
                                <option value="[JABATAN_KADES]">Jabatan Penandatangan ([JABATAN_KADES])</option>
                                <option value="[NAMA_KADES]">Nama Pejabat ([NAMA_KADES])</option>
                                <option value="[NIP_KADES]">NIP Pejabat ([NIP_KADES])</option>
                                <option value="[NAMA_DESA]">Nama Desa ([NAMA_DESA])</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Ribbon Row 2: Executive Formatting Tools -->
                <div class="p-2 flex flex-wrap items-center gap-1.5 bg-white text-slate-700 text-xs border-b border-slate-200">
                    
                    <!-- Undo / Redo Group -->
                    <div class="flex items-center gap-0.5">
                        <button type="button" @click="undo()" :disabled="historyIndex <= 0" :class="historyIndex <= 0 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'hover:bg-slate-100 text-slate-700 cursor-pointer'" class="p-1.5 rounded w-7 h-7 flex items-center justify-center border border-slate-200 transition-opacity" title="Undo (Ctrl+Z)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l6 6m-6-6l6-6"/></svg>
                        </button>
                        <button type="button" @click="redo()" :disabled="historyIndex >= historyStack.length - 1" :class="historyIndex >= historyStack.length - 1 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'hover:bg-slate-100 text-slate-700 cursor-pointer'" class="p-1.5 rounded w-7 h-7 flex items-center justify-center border border-slate-200 transition-opacity" title="Redo (Ctrl+Y / Ctrl+Shift+Z)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-6 6m6-6l-6-6"/></svg>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Font Family Selector -->
                    <select @change="execCmd('fontName', $event.target.value)" data-no-search="true" class="text-xs border border-slate-300 rounded px-2 py-1 bg-white hover:bg-slate-50 focus:outline-none focus:border-blue-600 font-medium no-search no-select2">
                        <option value="Times New Roman">Times New Roman (Resmi Permendagri)</option>
                        <option value="Bookman Old Style">Bookman Old Style (Naskah Dinas)</option>
                        <option value="Arial">Arial</option>
                        <option value="Calibri">Calibri</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                    </select>

                    <!-- Font Size Selector -->
                    <select @change="setFontSize($event.target.value)" data-no-search="true" class="text-xs border border-slate-300 rounded px-2 py-1 bg-white hover:bg-slate-50 focus:outline-none focus:border-blue-600 font-medium no-search no-select2">
                        <option value="9pt">9 pt</option>
                        <option value="10pt">10 pt</option>
                        <option value="11pt">11 pt</option>
                        <option value="12pt" selected>12 pt (Standar Naskah Dinas)</option>
                        <option value="14pt">14 pt (Judul Surat)</option>
                        <option value="16pt">16 pt (Kop Surat)</option>
                        <option value="18pt">18 pt</option>
                    </select>

                    <!-- Paragraph Heading Level -->
                    <select @change="execCmd('formatBlock', $event.target.value)" data-no-search="true" class="text-xs border border-slate-300 rounded px-2 py-1 bg-white hover:bg-slate-50 focus:outline-none focus:border-blue-600 font-medium no-search no-select2">
                        <option value="<p>">Paragraf Normal</option>
                        <option value="<h1>">Judul Utama (H1)</option>
                        <option value="<h2>">Sub-Judul (H2)</option>
                        <option value="<h3>">Heading 3 (H3)</option>
                    </select>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Bold, Italic, Underline, Strike -->
                    <div class="flex items-center gap-0.5">
                        <button type="button" @click="execCmd('bold')" class="p-1.5 rounded hover:bg-slate-100 font-black text-slate-900 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Tebal (Ctrl+B)">
                            <strong>B</strong>
                        </button>
                        <button type="button" @click="execCmd('italic')" class="p-1.5 rounded hover:bg-slate-100 italic font-serif text-slate-900 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Miring (Ctrl+I)">
                            <i>I</i>
                        </button>
                        <button type="button" @click="execCmd('underline')" class="p-1.5 rounded hover:bg-slate-100 underline text-slate-900 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Garis Bawah (Ctrl+U)">
                            <u>U</u>
                        </button>
                        <button type="button" @click="execCmd('strikeThrough')" class="p-1.5 rounded hover:bg-slate-100 line-through text-slate-700 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Coret (Strikethrough)">
                            <s>S</s>
                        </button>
                        <button type="button" @click="execCmd('subscript')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 text-[10px] cursor-pointer" title="Subscript (X₂)">
                            X<sub>2</sub>
                        </button>
                        <button type="button" @click="execCmd('superscript')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 transition-colors w-7 h-7 flex items-center justify-center border border-slate-200 text-[10px] cursor-pointer" title="Superscript (X²)">
                            X<sup>2</sup>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Colors: Text & Highlight -->
                    <div class="flex items-center gap-1">
                        <label class="relative cursor-pointer p-1 rounded hover:bg-slate-100 border border-slate-200 w-7 h-7 flex items-center justify-center" title="Warna Huruf">
                            <span class="font-extrabold text-xs text-slate-900">A</span>
                            <div class="w-3.5 h-1 bg-slate-900 absolute bottom-1 rounded-xs"></div>
                            <input type="color" @change="execCmd('foreColor', $event.target.value)" value="#000000" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                        </label>
                        <label class="relative cursor-pointer p-1 rounded hover:bg-slate-100 border border-slate-200 w-7 h-7 flex items-center justify-center" title="Warna Sorotan (Stabilo)">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5zM3 20h18v2H3v-2z"/></svg>
                            <input type="color" @change="execCmd('hiliteColor', $event.target.value)" value="#fef08a" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                        </label>
                        <button type="button" @click="execCmd('removeFormat')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 w-7 h-7 flex items-center justify-center cursor-pointer" title="Hapus Format">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Text Alignment -->
                    <div class="flex items-center gap-0.5">
                        <button type="button" @click="execCmd('justifyLeft')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Rata Kiri">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 5h12v2H3V9zm0 5h18v2H3v-2zm0 5h12v2H3v-2z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('justifyCenter')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Rata Tengah">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm3 5h12v2H6V9zm-3 5h18v2H3v-2zm3 5h12v2H6v-2z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('justifyRight')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Rata Kanan">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm6 5h12v2H9V9zm-6 5h18v2H3v-2zm6 5h12v2H9v-2z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('justifyFull')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 w-7 h-7 flex items-center justify-center border border-slate-200 cursor-pointer" title="Rata Kanan Kiri (Standar Naskah Dinas)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 5h18v2H3V9zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Line Spacing -->
                    <select @change="setLineSpacing($event.target.value)" data-no-search="true" class="text-xs border border-slate-300 rounded px-2 py-1 bg-white hover:bg-slate-50 focus:outline-none font-medium no-search no-select2" title="Jarak Spasi Antar Baris">
                        <option value="1.0">Spasi 1.0 (Rapat)</option>
                        <option value="1.15">Spasi 1.15</option>
                        <option value="1.3" selected>Spasi 1.3 (Standar Naskah Dinas)</option>
                        <option value="1.5">Spasi 1.5</option>
                        <option value="2.0">Spasi 2.0</option>
                    </select>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Lists, Indent & TAB Controls -->
                    <div class="flex items-center gap-0.5">
                        <button type="button" @click="execCmd('insertUnorderedList')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 w-7 h-7 flex items-center justify-center cursor-pointer" title="Daftar Poin (Bullets)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h2v2H4V6zm0 5h2v2H4v-2zm0 5h2v2H4v-2zm4-10h14v2H8V6zm0 5h14v2H8v-2zm0 5h14v2H8v-2z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('insertOrderedList')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 w-7 h-7 flex items-center justify-center cursor-pointer" title="Penomoran (Numbered List)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 5h2v3H2V5zm0 5h2v3H2v-3zm0 5h2v3H2v-3zm4-10h16v2H6V5zm0 5h16v2H6v-2zm0 5h16v2H6v-2z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('outdent')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 w-7 h-7 flex items-center justify-center cursor-pointer" title="Kurangi Indentasi (Shift + TAB)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M11 17h10v-2H11v2zm-8-5l4 4V8l-4 4zm8-1h10V9H11v2zm0-6v2h10V5H11z"/></svg>
                        </button>
                        <button type="button" @click="execCmd('indent')" class="p-1.5 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 w-7 h-7 flex items-center justify-center cursor-pointer" title="Tambah Indentasi (TAB)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 19h18v-2H3v2zm8-4h10v-2H11v2zm0-4h10V9H11v2zm-8-4h18V5H3v2zm4 4l-4-4v8l4-4z"/></svg>
                        </button>
                        <button type="button" @click="insertFirstLineIndent()" class="px-2 py-1 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold flex items-center gap-1 cursor-pointer" title="Indentasi Paragraf (1.25 cm)">
                            <span>Indent Paragraf</span>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-slate-200 mx-0.5"></div>

                    <!-- Insert Elements (Table, Line, Signature) -->
                    <div class="flex items-center gap-1">
                        <!-- Table Dropdown Tools -->
                        <div x-data="{ tableMenu: false }" class="relative">
                            <button type="button" @click="tableMenu = !tableMenu" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold border border-slate-300 transition-colors flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>Tabel ▾</span>
                            </button>
                            <div x-show="tableMenu" @click.outside="tableMenu = false" class="absolute left-0 mt-1 w-56 bg-white border border-slate-200 rounded-xl shadow-xl p-1.5 z-50 text-xs space-y-1" style="display: none;">
                                <button type="button" @click="insertBiodataTable(); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-900 font-semibold flex items-center gap-2 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Tabel Biodata Pemohon</span>
                                </button>
                                <button type="button" @click="insertGridTable(3, 4); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-900 font-semibold flex items-center gap-2 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    <span>Tabel Bergaris (3 Kolom × 4 Baris)</span>
                                </button>
                                <div class="border-t border-slate-100 my-1"></div>
                                <button type="button" @click="addTableRowBelow(); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 text-slate-700 flex items-center gap-2 cursor-pointer">
                                    <span>+ Tambah Baris di Bawah (Tab)</span>
                                </button>
                                <button type="button" @click="addTableRowAbove(); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 text-slate-700 flex items-center gap-2 cursor-pointer">
                                    <span>↑ Tambah Baris di Atas</span>
                                </button>
                                <button type="button" @click="deleteCurrentRow(); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-rose-50 text-rose-700 flex items-center gap-2 cursor-pointer">
                                    <span>✕ Hapus Baris Terpilih</span>
                                </button>
                                <button type="button" @click="toggleTableBorder(); tableMenu = false" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 text-slate-700 flex items-center gap-2 cursor-pointer">
                                    <span>⬚ Aktif/Nonaktifkan Garis Batas</span>
                                </button>
                            </div>
                        </div>

                        <!-- Insert Signature Block Helper -->
                        <button type="button" @click="insertSignatureBlock()" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold border border-slate-300 transition-colors flex items-center gap-1.5 cursor-pointer" title="Sisipkan Format Tanda Tangan Pejabat">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>+ Blok TTD</span>
                        </button>

                        <!-- Insert Horizontal Line -->
                        <button type="button" @click="execCmd('insertHorizontalRule')" class="px-2 py-1 rounded hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold cursor-pointer" title="Sisipkan Garis Pembatas">
                            Garis
                        </button>
                    </div>

                </div>

            </div>

            <!-- Visual Ruler Indicator (Standard A4 210mm Width) -->
            <div class="bg-slate-200 border-x border-t border-slate-300 px-8 py-1 hidden sm:flex items-center justify-between text-[9px] font-mono text-slate-600 select-none overflow-hidden w-[210mm] max-w-full mx-auto rounded-t-lg shadow-2xs">
                <span class="font-bold text-slate-800">| 0 cm (Batas Kiri)</span>
                <span class="hidden md:inline">| 2.5 cm (Tab Stop)</span>
                <span>| 5 cm</span>
                <span class="hidden md:inline">| 7.5 cm</span>
                <span>| 10 cm (Tengah)</span>
                <span class="hidden md:inline">| 12.5 cm</span>
                <span>| 15 cm</span>
                <span class="font-bold text-slate-800">| 17 cm (Batas Kanan)</span>
            </div>

            <!-- WYSIWYG Microsoft Word A4 Paper Canvas -->
            <div class="w-full overflow-x-auto pb-8 bg-slate-200/70 p-3 sm:p-6 rounded-xl border border-slate-300 flex justify-center min-w-0 shadow-inner">
                <div id="wordPaperA4"
                     :style="'transform: scale(' + (zoomLevel / 100) + '); transform-origin: top center; transition: transform 0.2s ease-out; width: 210mm; min-height: 297mm; max-width: 100%; box-sizing: border-box;'"
                     class="bg-white text-slate-900 font-serif relative transition-all duration-200 shadow-xl border border-slate-300">

                    <!-- Official Village Letterhead (Kop Surat) Header -->
                    <div x-show="denganKop" class="p-8 pb-3 border-b-4 border-double border-slate-900 select-none">
                        <div class="flex items-center gap-5">
                            <!-- Village Emblem / Logo -->
                            <div class="shrink-0 w-20 h-20 flex items-center justify-center">
                                <img src="{{ !empty($settings['logo_desa']) ? asset('storage/' . $settings['logo_desa']) : asset('storage/pengaturan/logo-desa.png') }}" 
                                     alt="Logo Desa" 
                                     class="w-full h-full object-contain"
                                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Coat_of_arms_of_Indonesia.svg/200px-Coat_of_arms_of_Indonesia.svg.png'">
                            </div>

                            <!-- Header Institutional Typography -->
                            <div class="flex-1 text-center font-serif leading-tight">
                                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800" x-text="kopLine1"></h4>
                                <h3 class="text-base font-bold uppercase tracking-wider text-slate-800" x-text="kopLine2"></h3>
                                <h2 class="text-lg font-black uppercase tracking-widest text-slate-900" x-text="kopLine3"></h2>
                                <p class="text-[10pt] font-normal text-slate-600 mt-1 italic" x-text="kopAlamat"></p>
                                <p class="text-[9pt] font-normal text-slate-600" x-text="kopKontak"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Editable Document Body (Microsoft Word Flow Layout with TAB Handler & Realtime Undo/Redo) -->
                    <div id="editableDocumentBody"
                         contenteditable="true"
                         @input="handleInput()"
                         @blur="updateContent()"
                         @keydown="handleKeyDown($event)"
                         class="p-8 sm:p-10 outline-none leading-relaxed text-slate-900 focus:ring-0"
                         style="min-height: 220mm; line-height: 1.35;">
                        {!! $initialHtml !!}
                    </div>

                </div>
            </div>

            <!-- Live Document Status Bar & Word Counter -->
            <div class="bg-white border border-slate-300 rounded-xl px-4 py-2 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600 shadow-2xs font-sans w-full min-w-0">
                <div class="flex items-center gap-4 flex-wrap">
                    <span class="font-semibold text-slate-700">Jumlah Kata: <strong class="text-slate-900 font-mono" x-text="wordCount">0</strong></span>
                    <span>&bull;</span>
                    <span>Karakter: <strong class="text-slate-900 font-mono" x-text="charCount">0</strong></span>
                    <span>&bull;</span>
                    <span>Paragraf: <strong class="text-slate-900 font-mono" x-text="paragraphCount">0</strong></span>
                </div>
                <div class="flex items-center gap-3 text-[11px] flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold border border-slate-200">
                        Format Kertas: A4 (210 × 297 mm)
                    </span>
                    <span class="text-slate-500">Tekan tombol <strong>TAB</strong> untuk indentasi atau berpindah sel tabel.</span>
                </div>
            </div>

        </div>

        <!-- Right Panel: Template Settings & Metadata -->
        <div class="w-full lg:w-72 xl:w-80 shrink-0 space-y-4 min-w-0">
            
            <!-- Real Form for Database Submission -->
            <form id="templateSaveForm" 
                  action="{{ isset($template) ? route('admin.master.template_surat.update', $template->id) : route('admin.master.template_surat.store') }}" 
                  method="POST">
                @csrf
                @if(isset($template))
                    @method('PUT')
                @endif

                <!-- Hidden Input Containing Full Document HTML -->
                <input type="hidden" name="canvas_json" id="hiddenContent" :value="documentHtml">

                <!-- Hidden Inputs for Settings -->
                <input type="hidden" name="nama" :value="nama">
                <input type="hidden" name="kategori_surat" :value="kategoriSurat">
                <input type="hidden" name="kode_surat" :value="kategoriSurat">
                <input type="hidden" name="format_nomor_surat" :value="formatNomorSurat">
                <input type="hidden" name="dengan_kop" :value="denganKop ? '1' : '0'">
                <input type="hidden" name="status_aktif" :value="statusAktif ? '1' : '0'">
                <input type="hidden" name="kop_line_1" :value="kopLine1">
                <input type="hidden" name="kop_line_2" :value="kopLine2">
                <input type="hidden" name="kop_line_3" :value="kopLine3">
                <input type="hidden" name="kop_alamat" :value="kopAlamat">
                <input type="hidden" name="kop_kontak" :value="kopKontak">
            </form>

            <!-- Card: Metadata & Settings -->
            <x-card class="space-y-4 border border-slate-200 shadow-2xs">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2 flex items-center justify-between">
                    <span>Parameter Template Surat</span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </h3>

                <!-- Nama Template -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Jenis Surat <span class="text-rose-500">*</span></label>
                    <input type="text" x-model="nama" required placeholder="Contoh: Surat Keterangan Domisili" class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>

                <!-- Kode Surat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Klasifikasi / Kode Surat <span class="text-rose-500">*</span></label>
                    <input type="text" x-model="kategoriSurat" required placeholder="Contoh: SKD, SKTM, SKU" class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>

                <!-- Format Penomoran Surat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Format Tata Penomoran Otomatis</label>
                    <input type="text" x-model="formatNomorSurat" placeholder="[NOMOR]/[KODE]/VII/[TAHUN]" class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 font-mono text-slate-900 focus:border-blue-600 focus:outline-none">
                    <span class="text-[10px] text-slate-500 block mt-1">Gunakan tag: [NOMOR], [KODE], [BULAN], [TAHUN]</span>
                </div>

                <!-- Toggle Kop Surat -->
                <div class="pt-2.5 border-t border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Kop Surat Resmi Instansi</span>
                            <span class="text-[11px] text-slate-500 block">Tampilkan kop lambang & alamat desa</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="denganKop" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Toggle Status Aktif -->
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-200">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Status Publikasi Template</span>
                        <span class="text-[11px] text-slate-500 block">Dapat dipilih pada menu pelayanan surat</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="statusAktif" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Tombol Simpan -->
                <div class="pt-3 border-t border-slate-200">
                    <button type="button" @click="submitTemplateForm()" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>Simpan Perubahan Naskah</span>
                    </button>
                </div>
            </x-card>

            <!-- Card: KOP Customization Editor -->
            <x-card x-show="denganKop" class="space-y-3 border border-slate-200 shadow-2xs" style="display: none;">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2 flex items-center justify-between">
                    <span>Penyesuaian Teks KOP</span>
                    <span class="text-[10px] text-blue-700 font-semibold">Khusus Naskah Ini</span>
                </h3>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Baris 1 (Pemerintah Kabupaten)</label>
                    <input type="text" x-model="kopLine1" class="w-full text-xs rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-900 focus:border-blue-600 focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Baris 2 (Kecamatan)</label>
                    <input type="text" x-model="kopLine2" class="w-full text-xs rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-900 focus:border-blue-600 focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Baris 3 (Pemerintah Desa)</label>
                    <input type="text" x-model="kopLine3" class="w-full text-xs rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-900 focus:border-blue-600 focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Alamat Kantor</label>
                    <textarea x-model="kopAlamat" rows="2" class="w-full text-xs rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-900 focus:border-blue-600 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Kontak & Website</label>
                    <input type="text" x-model="kopKontak" class="w-full text-xs rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-900 focus:border-blue-600 focus:outline-none">
                </div>
            </x-card>

        </div>

    </div>

    <!-- Print Preview Modal Dialog -->
    <div x-show="showPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs" style="display: none;">
        <div @click.outside="showPreviewModal = false" class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold">Pratinjau Hasil Cetak Naskah Surat (A4)</h3>
                </div>
                <button type="button" @click="showPreviewModal = false" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">✕</button>
            </div>
            <div class="p-6 overflow-y-auto bg-slate-100 flex justify-center flex-1">
                <div class="bg-white p-10 shadow-lg border border-slate-300 w-[210mm] min-h-[297mm] text-black font-serif">
                    <div x-show="denganKop" class="pb-3 border-b-4 border-double border-slate-900 mb-6 select-none">
                        <div class="flex items-center gap-5">
                            <div class="shrink-0 w-20 h-20 flex items-center justify-center">
                                <img src="{{ !empty($settings['logo_desa']) ? asset('storage/' . $settings['logo_desa']) : asset('storage/pengaturan/logo-desa.png') }}" 
                                     alt="Logo Desa" 
                                     class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 text-center font-serif leading-tight">
                                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800" x-text="kopLine1"></h4>
                                <h3 class="text-base font-bold uppercase tracking-wider text-slate-800" x-text="kopLine2"></h3>
                                <h2 class="text-lg font-black uppercase tracking-widest text-slate-900" x-text="kopLine3"></h2>
                                <p class="text-[10pt] font-normal text-slate-600 mt-1 italic" x-text="kopAlamat"></p>
                                <p class="text-[9pt] font-normal text-slate-600" x-text="kopKontak"></p>
                            </div>
                        </div>
                    </div>
                    <div class="leading-relaxed" x-html="documentHtml"></div>
                </div>
            </div>
            <div class="px-6 py-3 bg-white border-t border-slate-200 flex justify-end gap-2">
                <button type="button" @click="showPreviewModal = false" class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs cursor-pointer">Tutup Pratinjau</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function wordLetterEditor(config) {
    config = config || {};
    return {
        nama: config.nama || 'Surat Keterangan Tidak Mampu',
        kategoriSurat: config.kategoriSurat || 'SKTM',
        formatNomorSurat: config.formatNomorSurat || '[NOMOR]/SKTM/VII/[TAHUN]',
        denganKop: config.denganKop !== undefined ? Boolean(config.denganKop) : true,
        statusAktif: config.statusAktif !== undefined ? Boolean(config.statusAktif) : true,
        kopLine1: config.kopLine1 || config.defaultKopLine1 || '',
        kopLine2: config.kopLine2 || config.defaultKopLine2 || '',
        kopLine3: config.kopLine3 || config.defaultKopLine3 || '',
        kopAlamat: config.kopAlamat || config.defaultKopAlamat || '',
        kopKontak: config.kopKontak || config.defaultKopKontak || '',
        zoomLevel: 100,
        isFullscreen: false,
        documentHtml: '',
        showPreviewModal: false,
        wordCount: 0,
        charCount: 0,
        paragraphCount: 0,

        // ── UNDO / REDO HISTORY STATE STACK ──
        historyStack: [],
        historyIndex: -1,
        maxHistory: 50,
        historyDebounceTimer: null,

        initEditor: function() {
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                this.documentHtml = editorEl.innerHTML;
                this.pushHistory(true);
                this.updateCounters();
            }
        },

        pushHistory: function(force) {
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            var html = editorEl.innerHTML;

            // Don't push identical consecutive state unless forced
            if (!force && this.historyIndex >= 0 && this.historyStack[this.historyIndex] === html) {
                return;
            }

            // Truncate any forward redo history if we made new changes from a previous undo point
            if (this.historyIndex < this.historyStack.length - 1) {
                this.historyStack = this.historyStack.slice(0, this.historyIndex + 1);
            }

            this.historyStack.push(html);
            if (this.historyStack.length > this.maxHistory) {
                this.historyStack.shift();
            } else {
                this.historyIndex = this.historyStack.length - 1;
            }
        },

        handleInput: function() {
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();

                // Debounced history snapshot for typing
                clearTimeout(this.historyDebounceTimer);
                var self = this;
                this.historyDebounceTimer = setTimeout(function() {
                    self.pushHistory();
                }, 300);
            }
        },

        updateContent: function() {
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();
                this.pushHistory();
            }
        },

        undo: function() {
            if (this.historyIndex > 0) {
                this.historyIndex--;
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    editorEl.innerHTML = this.historyStack[this.historyIndex];
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                }
            }
        },

        redo: function() {
            if (this.historyIndex < this.historyStack.length - 1) {
                this.historyIndex++;
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    editorEl.innerHTML = this.historyStack[this.historyIndex];
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                }
            }
        },

        updateCounters: function() {
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            var text = editorEl.innerText || '';
            var cleanText = text.trim();
            this.charCount = cleanText.length;
            this.wordCount = cleanText ? cleanText.split(/\s+/).length : 0;
            this.paragraphCount = editorEl.querySelectorAll('p, h1, h2, h3, table').length || (cleanText ? 1 : 0);
        },

        handleKeyDown: function(e) {
            // ── 1. TAB KEY HANDLER (Microsoft Word & Tables) ──
            if (e.key === 'Tab') {
                e.preventDefault();
                var selection = window.getSelection();
                if (!selection.rangeCount) return;
                var range = selection.getRangeAt(0);

                // Check if inside a table cell (TD / TH)
                var cell = range.startContainer;
                while (cell && cell.nodeName !== 'TD' && cell.nodeName !== 'TH' && cell !== e.currentTarget) {
                    cell = cell.parentNode;
                }

                if (cell && (cell.nodeName === 'TD' || cell.nodeName === 'TH')) {
                    var row = cell.parentNode;
                    var table = row.closest('table');
                    if (table) {
                        var allCells = Array.from(table.querySelectorAll('td, th'));
                        var currentIndex = allCells.indexOf(cell);

                        if (e.shiftKey) {
                            // Shift + Tab: Move to previous cell
                            if (currentIndex > 0) {
                                this.focusElement(allCells[currentIndex - 1]);
                            }
                        } else {
                            // Tab: Move to next cell OR automatically append new row if at the end!
                            if (currentIndex < allCells.length - 1) {
                                this.focusElement(allCells[currentIndex + 1]);
                            } else {
                                // At last cell of table: add a brand new row (Word behavior)
                                var colCount = row.children.length;
                                var newRow = document.createElement('tr');
                                for (var i = 0; i < colCount; i++) {
                                    var newTd = document.createElement('td');
                                    newTd.style.cssText = row.children[i].style.cssText || 'padding: 3px 0;';
                                    newTd.innerHTML = '<br>';
                                    newRow.appendChild(newTd);
                                }
                                row.parentNode.appendChild(newRow);
                                this.focusElement(newRow.children[0]);
                            }
                        }
                        this.documentHtml = document.getElementById('editableDocumentBody').innerHTML;
                        this.pushHistory(true);
                        return;
                    }
                }

                // Outside table: standard tab insertion
                if (e.shiftKey) {
                    document.execCommand('outdent', false, null);
                } else {
                    // Insert 4 non-breaking spaces for a clean standard tab indent
                    var tabNode = document.createTextNode('\u00A0\u00A0\u00A0\u00A0');
                    range.deleteContents();
                    range.insertNode(tabNode);
                    range.setStartAfter(tabNode);
                    range.setEndAfter(tabNode);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
                this.documentHtml = document.getElementById('editableDocumentBody').innerHTML;
                this.pushHistory(true);
                return;
            }

            // ── 2. KEYBOARD SHORTCUTS (Undo, Redo, Save, Bold, Italic, Underline) ──
            if (e.ctrlKey || e.metaKey) {
                if (e.key === 'z' || e.key === 'Z') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        this.redo();
                    } else {
                        this.undo();
                    }
                } else if (e.key === 'y' || e.key === 'Y') {
                    e.preventDefault();
                    this.redo();
                } else if (e.key === 's' || e.key === 'S') {
                    e.preventDefault();
                    this.submitTemplateForm();
                } else if (e.key === 'b' || e.key === 'B') {
                    e.preventDefault();
                    this.execCmd('bold');
                } else if (e.key === 'i' || e.key === 'I') {
                    e.preventDefault();
                    this.execCmd('italic');
                } else if (e.key === 'u' || e.key === 'U') {
                    e.preventDefault();
                    this.execCmd('underline');
                }
            }
        },

        focusElement: function(el) {
            if (!el) return;
            var range = document.createRange();
            var sel = window.getSelection();
            range.selectNodeContents(el);
            range.collapse(false);
            sel.removeAllRanges();
            sel.addRange(range);
        },

        execCmd: function(command, value) {
            if (value === undefined) value = null;
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            editorEl.focus();
            document.execCommand(command, false, value);
            this.documentHtml = editorEl.innerHTML;
            this.updateCounters();
            this.pushHistory(true);
        },

        setFontSize: function(size) {
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            editorEl.focus();
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var range = selection.getRangeAt(0);
            var span = document.createElement('span');
            span.style.fontSize = size;
            try {
                range.surroundContents(span);
            } catch(e) {
                document.execCommand('fontSize', false, '3');
            }
            this.documentHtml = editorEl.innerHTML;
            this.updateCounters();
            this.pushHistory(true);
        },

        setLineSpacing: function(val) {
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                editorEl.style.lineHeight = val;
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();
                this.pushHistory(true);
            }
        },

        insertFirstLineIndent: function() {
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            editorEl.focus();
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var node = selection.anchorNode;
            while (node && node.nodeName !== 'P' && node.nodeName !== 'DIV' && node !== editorEl) {
                node = node.parentNode;
            }
            if (node && (node.nodeName === 'P' || node.nodeName === 'DIV')) {
                node.style.textIndent = (node.style.textIndent === '40px' ? '0px' : '40px');
            } else {
                document.execCommand('insertHTML', false, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            }
            this.documentHtml = editorEl.innerHTML;
            this.updateCounters();
            this.pushHistory(true);
        },

        insertVariable: function(tag) {
            var editorEl = document.getElementById('editableDocumentBody');
            if (!editorEl) return;
            editorEl.focus();
            var selection = window.getSelection();
            if (!selection.rangeCount) {
                editorEl.innerHTML += tag;
            } else {
                var range = selection.getRangeAt(0);
                range.deleteContents();
                var textNode = document.createTextNode(tag);
                range.insertNode(textNode);
                range.setStartAfter(textNode);
                range.setEndAfter(textNode);
                selection.removeAllRanges();
                selection.addRange(range);
            }
            this.documentHtml = editorEl.innerHTML;
            this.updateCounters();
            this.pushHistory(true);
        },

        insertBiodataTable: function() {
            var tableHtml = '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-top: 10px; margin-bottom: 16px;"><tbody>' +
                '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                '<tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>' +
                '</tbody></table>' +
                '<p><br></p>';
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                editorEl.focus();
                document.execCommand('insertHTML', false, tableHtml);
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();
                this.pushHistory(true);
            }
        },

        insertGridTable: function(cols, rows) {
            cols = cols || 3;
            rows = rows || 4;
            var tableHtml = '<table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; margin: 12px 0;"><tbody>';
            for (var r = 0; r < rows; r++) {
                tableHtml += '<tr>';
                for (var c = 0; c < cols; c++) {
                    var isHeader = (r === 0);
                    var tag = isHeader ? 'th' : 'td';
                    var bg = isHeader ? 'background-color: #f1f5f9; font-weight: bold;' : '';
                    tableHtml += '<' + tag + ' style="border: 1px solid #000000; padding: 6px 8px; text-align: left; ' + bg + '">' + (isHeader ? 'Kolom ' + (c + 1) : 'Data') + '</' + tag + '>';
                }
                tableHtml += '</tr>';
            }
            tableHtml += '</tbody></table><p><br></p>';
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                editorEl.focus();
                document.execCommand('insertHTML', false, tableHtml);
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();
                this.pushHistory(true);
            }
        },

        addTableRowBelow: function() {
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var range = selection.getRangeAt(0);
            var cell = range.startContainer;
            while (cell && cell.nodeName !== 'TD' && cell.nodeName !== 'TH') {
                cell = cell.parentNode;
            }
            if (cell) {
                var row = cell.parentNode;
                var colCount = row.children.length;
                var newRow = document.createElement('tr');
                for (var i = 0; i < colCount; i++) {
                    var newTd = document.createElement('td');
                    newTd.style.cssText = row.children[i].style.cssText;
                    newTd.innerHTML = '<br>';
                    newRow.appendChild(newTd);
                }
                row.parentNode.insertBefore(newRow, row.nextSibling);
                this.focusElement(newRow.children[0]);
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                    this.pushHistory(true);
                }
            }
        },

        addTableRowAbove: function() {
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var range = selection.getRangeAt(0);
            var cell = range.startContainer;
            while (cell && cell.nodeName !== 'TD' && cell.nodeName !== 'TH') {
                cell = cell.parentNode;
            }
            if (cell) {
                var row = cell.parentNode;
                var colCount = row.children.length;
                var newRow = document.createElement('tr');
                for (var i = 0; i < colCount; i++) {
                    var newTd = document.createElement('td');
                    newTd.style.cssText = row.children[i].style.cssText;
                    newTd.innerHTML = '<br>';
                    newRow.appendChild(newTd);
                }
                row.parentNode.insertBefore(newRow, row);
                this.focusElement(newRow.children[0]);
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                    this.pushHistory(true);
                }
            }
        },

        deleteCurrentRow: function() {
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var range = selection.getRangeAt(0);
            var cell = range.startContainer;
            while (cell && cell.nodeName !== 'TD' && cell.nodeName !== 'TH') {
                cell = cell.parentNode;
            }
            if (cell) {
                var row = cell.parentNode;
                row.remove();
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                    this.pushHistory(true);
                }
            }
        },

        toggleTableBorder: function() {
            var selection = window.getSelection();
            if (!selection.rangeCount) return;
            var range = selection.getRangeAt(0);
            var cell = range.startContainer;
            while (cell && cell.nodeName !== 'TABLE' && cell !== document.getElementById('editableDocumentBody')) {
                cell = cell.parentNode;
            }
            if (cell && cell.nodeName === 'TABLE') {
                var hasBorder = cell.style.border && cell.style.border.indexOf('none') === -1;
                if (hasBorder) {
                    cell.style.border = 'none';
                    cell.querySelectorAll('td, th').forEach(function(td) { td.style.border = 'none'; });
                } else {
                    cell.style.border = '1px solid #000000';
                    cell.querySelectorAll('td, th').forEach(function(td) { td.style.border = '1px solid #000000'; });
                }
                var editorEl = document.getElementById('editableDocumentBody');
                if (editorEl) {
                    this.documentHtml = editorEl.innerHTML;
                    this.updateCounters();
                    this.pushHistory(true);
                }
            }
        },

        insertSignatureBlock: function() {
            var signatureHtml = '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody>' +
                '<tr><td style="width: 50%;"></td>' +
                '<td style="width: 50%; text-align: center; vertical-align: top;">' +
                '<p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p>' +
                '<p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p>' +
                '<p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p>' +
                '<p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p>' +
                '</td></tr></tbody></table>';
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                editorEl.focus();
                document.execCommand('insertHTML', false, signatureHtml);
                this.documentHtml = editorEl.innerHTML;
                this.updateCounters();
                this.pushHistory(true);
            }
        },

        zoomIn: function() {
            if (this.zoomLevel < 150) this.zoomLevel += 10;
        },

        zoomOut: function() {
            if (this.zoomLevel > 60) this.zoomLevel -= 10;
        },

        openPreview: function() {
            this.updateContent();
            this.showPreviewModal = true;
        },

        submitTemplateForm: function() {
            this.updateContent();
            if (!this.nama.trim()) {
                alert('Silakan masukkan nama template surat!');
                return;
            }
            if (!this.kategoriSurat.trim()) {
                alert('Silakan masukkan kode/kategori surat!');
                return;
            }
            document.getElementById('hiddenContent').value = this.documentHtml;
            document.getElementById('templateSaveForm').submit();
        },

        loadPreset: function(type) {
            if (!type) return;
            if (!confirm('Apakah Anda yakin ingin memuat format surat ' + type.toUpperCase() + '? Naskah yang sedang diedit akan digantikan.')) {
                return;
            }

            var title = '';
            var code = '';
            var format = '';
            var html = '';

            if (type === 'sktm') {
                title = 'Surat Keterangan Tidak Mampu';
                code = 'SKTM';
                format = '[NOMOR]/SKTM/VII/[TAHUN]';
                html = '<div style="text-align: center; margin-bottom: 24px;">' +
                    '<h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN TIDAK MAMPU</h3>' +
                    '<p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>' +
                    '</div>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;"><tbody>' +
                    '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NO_KK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>' +
                    '</tbody></table>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Berdasarkan data dan pengamatan di lapangan, yang bersangkutan adalah benar-benar penduduk Desa [NAMA_DESA] yang tergolong dalam keluarga prasejahtera / tidak mampu secara ekonomi. Surat keterangan ini dibuat untuk keperluan: <strong>[KEPERLUAN]</strong>.</p>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">Demikian surat keterangan tidak mampu ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody><tr><td style="width: 50%;"></td><td style="width: 50%; text-align: center; vertical-align: top;"><p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p><p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p><p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p><p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p></td></tr></tbody></table>';
            } else if (type === 'sku') {
                title = 'Surat Keterangan Usaha';
                code = 'SKU';
                format = '[NOMOR]/SKU/VII/[TAHUN]';
                html = '<div style="text-align: center; margin-bottom: 24px;">' +
                    '<h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN USAHA</h3>' +
                    '<p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>' +
                    '</div>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;"><tbody>' +
                    '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>' +
                    '</tbody></table>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Bahwa orang yang namanya tersebut di atas adalah benar-benar warga penduduk Desa [NAMA_DESA] dan memiliki usaha mandiri yang bergerak di bidang: <strong>[KEPERLUAN]</strong> yang beralamat di wilayah Desa [NAMA_DESA].</p>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">Demikian surat keterangan usaha ini dibuat dengan sebenarnya untuk kelengkapan administrasi dan legalitas usaha yang bersangkutan.</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody><tr><td style="width: 50%;"></td><td style="width: 50%; text-align: center; vertical-align: top;"><p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p><p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p><p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p><p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p></td></tr></tbody></table>';
            } else if (type === 'skd') {
                title = 'Surat Keterangan Domisili';
                code = 'SKD';
                format = '[NOMOR]/SKD/VII/[TAHUN]';
                html = '<div style="text-align: center; margin-bottom: 24px;">' +
                    '<h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN DOMISILI</h3>' +
                    '<p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>' +
                    '</div>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;"><tbody>' +
                    '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NO_KK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Alamat KTP</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT]</td></tr>' +
                    '</tbody></table>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Berdasarkan pencatatan data kependudukan dan surat pengantar dari Ketua RT/RW setempat, nama tersebut di atas adalah benar-benar bertempat tinggal dan berdomisili di wilayah RT [RT] / RW [RW], Dusun [DUSUN], Desa [NAMA_DESA]. Surat keterangan ini diterbitkan untuk dipergunakan sebagai: <strong>[KEPERLUAN]</strong>.</p>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">Demikian surat keterangan domisili ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody><tr><td style="width: 50%;"></td><td style="width: 50%; text-align: center; vertical-align: top;"><p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p><p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p><p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p><p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p></td></tr></tbody></table>';
            } else if (type === 'skck') {
                title = 'Surat Pengantar Kelakuan Baik (SKCK)';
                code = 'SKCK';
                format = '[NOMOR]/SKCK/VII/[TAHUN]';
                html = '<div style="text-align: center; margin-bottom: 24px;">' +
                    '<h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT PENGANTAR KELAKUAN BAIK</h3>' +
                    '<p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>' +
                    '</div>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;"><tbody>' +
                    '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>' +
                    '</tbody></table>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Sepanjang pengetahuan kami dan catatan yang ada di Desa [NAMA_DESA], orang yang bersangkutan berkelakuan baik, tidak sedang menjalani proses pidana, dan tidak pernah terlibat tindakan kriminalitas atau penyalahgunaan narkotika. Surat pengantar ini dibuat sebagai kelengkapan permohonan penerbitan <strong>Surat Keterangan Catatan Kepolisian (SKCK)</strong> di Kepolisian Sektor [NAMA_KECAMATAN].</p>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody><tr><td style="width: 50%;"></td><td style="width: 50%; text-align: center; vertical-align: top;"><p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p><p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p><p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p><p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p></td></tr></tbody></table>';
            } else if (type === 'belum_menikah') {
                title = 'Surat Keterangan Belum Menikah';
                code = 'SKBM';
                format = '[NOMOR]/SKBM/VII/[TAHUN]';
                html = '<div style="text-align: center; margin-bottom: 24px;">' +
                    '<h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN BELUM MENIKAH</h3>' +
                    '<p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>' +
                    '</div>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;"><tbody>' +
                    '<tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Status Perkawinan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">Belum Kawin / Belum Menikah</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>' +
                    '<tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>' +
                    '</tbody></table>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">Berdasarkan pengakuan pemohon serta data yang ada pada kami, yang bersangkutan sampai dengan dikeluarkannya surat keterangan ini adalah <strong>BENAR-BENAR BELUM PERNAH MENIKAH</strong> dengan siapapun. Surat keterangan ini dipergunakan untuk keperluan: <strong>[KEPERLUAN]</strong>.</p>' +
                    '<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 30px;"><tbody><tr><td style="width: 50%;"></td><td style="width: 50%; text-align: center; vertical-align: top;"><p style="margin: 0 0 6px 0;">[NAMA_DESA], [TANGGAL_SURAT]</p><p style="font-weight: bold; margin: 0 0 65px 0;">[JABATAN_KADES]</p><p style="font-weight: bold; text-decoration: underline; margin: 0;">[NAMA_KADES]</p><p style="margin: 2px 0 0 0; font-size: 10pt;">[NIP_KADES]</p></td></tr></tbody></table>';
            }

            this.nama = title;
            this.kategoriSurat = code;
            this.formatNomorSurat = format;
            var editorEl = document.getElementById('editableDocumentBody');
            if (editorEl) {
                editorEl.innerHTML = html;
                this.documentHtml = html;
                this.updateCounters();
                this.pushHistory(true);
            }
        }
    };
}
</script>
@endpush
