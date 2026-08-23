<?php

namespace Database\Seeders;

use App\Models\BumdesLaporan;
use App\Models\BumdesUnit;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class BumdesSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'nama' => 'Unit Usaha Tirta Jaya Lestari',
                'deskripsi' => 'Pengelolaan sistem distribusi air bersih perpipaan (PAMSIMAS) melayani 4 dusun dan fasilitas umum desa.',
                'penanggung_jawab' => 'Bambang Wijaya',
                'status' => 'aktif',
                'laporans' => [
                    [
                        'jenis' => 'keuangan',
                        'judul' => 'Laporan Keuangan Tahunan Unit Tirta Jaya 2025',
                        'file_path' => 'bumdes/laporan-keuangan-tirta-2025.pdf',
                        'tanggal' => '2026-01-15',
                        'deskripsi' => 'Rekapitulasi pendapatan tagihan air dan biaya pemeliharaan jaringan pipa.',
                    ],
                    [
                        'jenis' => 'kegiatan',
                        'judul' => 'Laporan Pemeliharaan Pompa & Pipa Distribusi Dusun Tirta Kencana',
                        'file_path' => 'bumdes/laporan-kegiatan-pipanisasi-2026.pdf',
                        'tanggal' => '2026-04-10',
                        'deskripsi' => 'Dokumentasi penggantian valve dan perluasan jaringan perpipaan 300 meter.',
                    ]
                ]
            ],
            [
                'nama' => 'Unit Usaha Toko Pertanian & Saprodi Desa',
                'deskripsi' => 'Penyediaan pupuk organik, bibit unggul padi/jagung, pakan ternak, dan persewaan traktor bagi kelompok tani.',
                'penanggung_jawab' => 'Edi Sugianto',
                'status' => 'aktif',
                'laporans' => [
                    [
                        'jenis' => 'keuangan',
                        'judul' => 'Laporan Arus Kas & Laba Rugi Toko Saprodi 2025',
                        'file_path' => 'bumdes/laporan-keuangan-saprodi-2025.pdf',
                        'tanggal' => '2026-01-20',
                        'deskripsi' => 'Laporan penjualan sarana produksi pertanian dan dividen untuk kas desa.',
                    ]
                ]
            ],
            [
                'nama' => 'Unit Usaha Ekowisata & Sentra Kuliner Desa',
                'deskripsi' => 'Pengelolaan kawasan wisata sumber mata air, spot foto persawahan, dan 12 kios pujasera kuliner UMKM lokal.',
                'penanggung_jawab' => 'Hendra Kurniawan',
                'status' => 'aktif',
                'laporans' => [
                    [
                        'jenis' => 'kegiatan',
                        'judul' => 'Laporan Kunjungan Wisatawan & Pendapatan Retribusi Semester I 2026',
                        'file_path' => 'bumdes/laporan-wisata-sem1-2026.pdf',
                        'tanggal' => '2026-07-05',
                        'deskripsi' => 'Statistik kunjungan 14.500 wisatawan dan perputaran ekonomi lapak pedagang.',
                    ]
                ]
            ],
            [
                'nama' => 'Unit Usaha Agen Layanan Keuangan Digital',
                'deskripsi' => 'Layanan loket pembayaran PBB-P2, tagihan listrik, BPJS Kesehatan, transfer perbankan, dan tarik tunai cepat.',
                'penanggung_jawab' => 'Tri Mulyono',
                'status' => 'aktif',
                'laporans' => []
            ]
        ];

        foreach ($units as $uData) {
            $unit = BumdesUnit::firstOrCreate(
                ['nama' => $uData['nama']],
                [
                    'deskripsi' => $uData['deskripsi'],
                    'penanggung_jawab' => $uData['penanggung_jawab'],
                    'status' => $uData['status'],
                ]
            );

            foreach ($uData['laporans'] as $lap) {
                // Generate sample PDF document
                ImageGenerator::createSamplePdf(
                    $lap['file_path'],
                    $lap['judul']
                );

                BumdesLaporan::updateOrCreate(
                    [
                        'unit_id' => $unit->id,
                        'judul' => $lap['judul'],
                    ],
                    [
                        'jenis' => $lap['jenis'],
                        'file_path' => $lap['file_path'],
                        'tanggal' => $lap['tanggal'],
                        'deskripsi' => $lap['deskripsi'],
                    ]
                );
            }
        }
    }
}
