<?php

namespace Database\Seeders;

use App\Models\TemplateSurat;
use Illuminate\Database\Seeder;

class TemplateSuratSeeder extends Seeder
{
    public function run(): void
    {
        $defaultKop = [
            'kop_line_1' => 'PEMERINTAH KABUPATEN NIRWANA RAYA',
            'kop_line_2' => 'KECAMATAN ASTRAGUNA',
            'kop_line_3' => 'PEMERINTAH DESA CANDRALOKA',
            'kop_alamat' => 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya 99881',
            'kop_kontak' => 'Website: https://candraloka.desa.id | Email: kontak@candraloka.desa.id | Telp: +62 811-7788-9900',
        ];

        // 1. Surat Keterangan Tidak Mampu (SKTM)
        $sktmHtml = <<<HTML
<div style="text-align: center; margin-bottom: 24px;">
    <h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN TIDAK MAMPU</h3>
    <p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>
</div>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:
</p>

<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;">
    <tbody>
        <tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>
        <tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>
        <tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[NO_KK]</td></tr>
        <tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>
        <tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>
        <tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>
        <tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>
        <tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>
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

        TemplateSurat::updateOrCreate(
            ['kategori_surat' => 'SKTM'],
            array_merge($defaultKop, [
                'nama' => 'Surat Keterangan Tidak Mampu',
                'dengan_kop' => true,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
                'margin_right' => 20,
                'format_nomor_surat' => '[NOMOR]/SKTM/VII/[TAHUN]',
                'content' => $sktmHtml,
                'is_active' => true,
            ])
        );

        // 2. Surat Keterangan Usaha (SKU)
        $skuHtml = <<<HTML
<div style="text-align: center; margin-bottom: 24px;">
    <h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN USAHA</h3>
    <p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>
</div>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:
</p>

<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;">
    <tbody>
        <tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>
        <tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>
        <tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[NO_KK]</td></tr>
        <tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>
        <tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>
        <tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>
        <tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>
        <tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>
    </tbody>
</table>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Bahwa orang yang namanya tersebut di atas adalah benar-benar warga penduduk Desa [NAMA_DESA] dan sepanjang pengetahuan kami memiliki usaha aktif yang bergerak di bidang: <strong>[KEPERLUAN]</strong> yang beralamat di wilayah Desa [NAMA_DESA].
</p>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">
    Demikian surat keterangan usaha ini dibuat dengan sebenarnya untuk kelengkapan administrasi dan legalitas usaha yang bersangkutan.
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

        TemplateSurat::updateOrCreate(
            ['kategori_surat' => 'SKU'],
            array_merge($defaultKop, [
                'nama' => 'Surat Keterangan Usaha',
                'dengan_kop' => true,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
                'margin_right' => 20,
                'format_nomor_surat' => '[NOMOR]/SKU/VII/[TAHUN]',
                'content' => $skuHtml,
                'is_active' => true,
            ])
        );

        // 3. Surat Keterangan Domisili (SKD)
        $skdHtml = <<<HTML
<div style="text-align: center; margin-bottom: 24px;">
    <h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT KETERANGAN DOMISILI</h3>
    <p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>
</div>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:
</p>

<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;">
    <tbody>
        <tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>
        <tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>
        <tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[NO_KK]</td></tr>
        <tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>
        <tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>
        <tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>
        <tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>
        <tr><td style="padding: 3px 0;">Alamat KTP</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT]</td></tr>
    </tbody>
</table>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Berdasarkan pencatatan data kependudukan dan surat pengantar dari Ketua RT/RW setempat, nama tersebut di atas adalah benar-benar bertempat tinggal dan berdomisili di wilayah RT [RT] / RW [RW], Dusun [DUSUN], Desa [NAMA_DESA]. Surat keterangan ini diterbitkan untuk dipergunakan sebagai: <strong>[KEPERLUAN]</strong>.
</p>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">
    Demikian surat keterangan domisili ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
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

        TemplateSurat::updateOrCreate(
            ['kategori_surat' => 'SKD'],
            array_merge($defaultKop, [
                'nama' => 'Surat Keterangan Domisili',
                'dengan_kop' => true,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
                'margin_right' => 20,
                'format_nomor_surat' => '[NOMOR]/SKD/VII/[TAHUN]',
                'content' => $skdHtml,
                'is_active' => true,
            ])
        );

        // 4. Surat Pengantar Kelakuan Baik (SKCK)
        $skckHtml = <<<HTML
<div style="text-align: center; margin-bottom: 24px;">
    <h3 style="font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase;">SURAT PENGANTAR KELAKUAN BAIK</h3>
    <p style="font-size: 11pt; margin: 4px 0 0 0;">Nomor: [NOMOR_SURAT]</p>
</div>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Yang bertanda tangan di bawah ini Kepala Desa [NAMA_DESA], [NAMA_KECAMATAN], [NAMA_KABUPATEN], menerangkan dengan sebenarnya bahwa:
</p>

<table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 16px;">
    <tbody>
        <tr><td style="width: 190px; padding: 3px 0;">Nama Lengkap</td><td style="width: 15px; padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NAMA]</td></tr>
        <tr><td style="padding: 3px 0;">NIK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0; font-weight: bold;">[NIK]</td></tr>
        <tr><td style="padding: 3px 0;">Nomor KK</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[NO_KK]</td></tr>
        <tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[TEMPAT_TANGGAL_LAHIR]</td></tr>
        <tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[JENIS_KELAMIN]</td></tr>
        <tr><td style="padding: 3px 0;">Agama</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[AGAMA]</td></tr>
        <tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[PEKERJAAN]</td></tr>
        <tr><td style="padding: 3px 0;">Alamat Domisili</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[ALAMAT], RT [RT] / RW [RW], Dusun [DUSUN]</td></tr>
    </tbody>
</table>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 16px;">
    Sepanjang pengetahuan kami dan catatan yang ada di Desa [NAMA_DESA], orang yang bersangkutan berkelakuan baik, tidak sedang menjalani proses pidana, dan tidak pernah terlibat tindakan kriminalitas atau penyalahgunaan narkotika. Surat pengantar ini dibuat sebagai kelengkapan permohonan penerbitan <strong>Surat Keterangan Catatan Kepolisian (SKCK)</strong> di Kepolisian Sektor [NAMA_KECAMATAN].
</p>

<p style="text-align: justify; text-indent: 40px; margin-bottom: 30px;">
    Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
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

        TemplateSurat::updateOrCreate(
            ['kategori_surat' => 'SKCK'],
            array_merge($defaultKop, [
                'nama' => 'Surat Pengantar Kelakuan Baik (SKCK)',
                'dengan_kop' => true,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
                'margin_right' => 20,
                'format_nomor_surat' => '[NOMOR]/SKCK/VII/[TAHUN]',
                'content' => $skckHtml,
                'is_active' => true,
            ])
        );
    }
}
