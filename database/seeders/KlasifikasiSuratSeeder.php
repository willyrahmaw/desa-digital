<?php

namespace Database\Seeders;

use App\Models\KlasifikasiSurat;
use App\Models\PengaturanPenomoran;
use Illuminate\Database\Seeder;

class KlasifikasiSuratSeeder extends Seeder
{
    public function run(): void
    {
        $klasifikasi = [
            ['nama' => 'Surat Keterangan Tidak Mampu',      'kode' => '470', 'kategori' => 'Sosial',          'deskripsi' => 'Surat keterangan untuk warga kurang mampu', 'urutan' => 1],
            ['nama' => 'Surat Keterangan Usaha',            'kode' => '503', 'kategori' => 'Ekonomi',         'deskripsi' => 'Surat keterangan untuk pelaku usaha',       'urutan' => 2],
            ['nama' => 'Surat Keterangan Domisili',         'kode' => '474', 'kategori' => 'Kependudukan',    'deskripsi' => 'Surat keterangan tempat tinggal/domisili',  'urutan' => 3],
            ['nama' => 'Surat Keterangan Kelahiran',        'kode' => '474.1', 'kategori' => 'Kependudukan', 'deskripsi' => 'Surat keterangan lahir warga',               'urutan' => 4],
            ['nama' => 'Surat Keterangan Kematian',         'kode' => '474.3', 'kategori' => 'Kependudukan', 'deskripsi' => 'Surat keterangan meninggal dunia',           'urutan' => 5],
            ['nama' => 'Surat Keterangan Pindah',           'kode' => '474.2', 'kategori' => 'Kependudukan', 'deskripsi' => 'Surat keterangan pindah domisili',           'urutan' => 6],
            ['nama' => 'Surat Keterangan Catatan Kepolisian','kode' => '472', 'kategori' => 'Keamanan',       'deskripsi' => 'Surat pengantar SKCK',                      'urutan' => 7],
            ['nama' => 'Surat Keterangan Ahli Waris',       'kode' => '590', 'kategori' => 'Hukum',          'deskripsi' => 'Surat keterangan ahli waris',               'urutan' => 8],
            ['nama' => 'Surat Keterangan Belum Menikah',    'kode' => '474.5', 'kategori' => 'Kependudukan', 'deskripsi' => 'Surat keterangan status perkawinan',         'urutan' => 9],
            ['nama' => 'Surat Pengantar',                   'kode' => '400', 'kategori' => 'Administrasi',   'deskripsi' => 'Surat pengantar ke instansi lain',          'urutan' => 10],
            ['nama' => 'Surat Izin Keramaian',              'kode' => '300', 'kategori' => 'Keamanan',       'deskripsi' => 'Surat izin mengadakan kegiatan keramaian',  'urutan' => 11],
            ['nama' => 'Surat Rekomendasi',                 'kode' => '005', 'kategori' => 'Administrasi',   'deskripsi' => 'Surat rekomendasi dari desa',               'urutan' => 12],
        ];

        foreach ($klasifikasi as $item) {
            KlasifikasiSurat::firstOrCreate(['kode' => $item['kode']], array_merge($item, ['status' => 'aktif']));
        }

        // Seed default penomoran formats for each major jenis surat
        $formats = [
            [
                'nama_format' => 'Format SKTM Tahunan',
                'jenis_surat' => 'SKTM',
                'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
                'separator'   => '/',
                'reset_nomor' => 'yearly',
                'digit_nomor' => 3,
                'status'      => true,
            ],
            [
                'nama_format' => 'Format SKU Tahunan',
                'jenis_surat' => 'SKU',
                'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
                'separator'   => '/',
                'reset_nomor' => 'yearly',
                'digit_nomor' => 3,
                'status'      => true,
            ],
            [
                'nama_format' => 'Format SKD Tahunan',
                'jenis_surat' => 'SKD',
                'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
                'separator'   => '/',
                'reset_nomor' => 'yearly',
                'digit_nomor' => 3,
                'status'      => true,
            ],
            [
                'nama_format' => 'Format SKCK Tahunan',
                'jenis_surat' => 'SKCK',
                'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
                'separator'   => '/',
                'reset_nomor' => 'yearly',
                'digit_nomor' => 3,
                'status'      => true,
            ],
        ];

        foreach ($formats as $fmt) {
            PengaturanPenomoran::firstOrCreate(['jenis_surat' => $fmt['jenis_surat']], $fmt);
        }
    }
}
