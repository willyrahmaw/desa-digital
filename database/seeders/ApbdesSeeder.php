<?php

namespace Database\Seeders;

use App\Models\Apbdes;
use Illuminate\Database\Seeder;

class ApbdesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing records to ensure clean detailed seeding
        Apbdes::truncate();

        $tahun = (int)date('Y');

        $items = [
            // ── 1. PENDAPATAN DESA (AKUN 4) ──────────────────────────────────
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.1. Pendapatan Asli Desa (PADes)',
                'kategori' => 'Hasil Usaha BUMDes',
                'jumlah' => 45000000,
                'realisasi' => 38000000,
                'tanggal' => now(),
                'keterangan' => 'Bagi hasil keuntungan BUMDes Desa Digital',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.1. Pendapatan Asli Desa (PADes)',
                'kategori' => 'Hasil Aset Desa (Sewa Tanah Kas Desa)',
                'jumlah' => 25000000,
                'realisasi' => 25000000,
                'tanggal' => now(),
                'keterangan' => 'Sewa lahan bengkok & kios desa',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.1. Pendapatan Asli Desa (PADes)',
                'kategori' => 'Swadaya & Swakelola Masyarakat',
                'jumlah' => 15000000,
                'realisasi' => 12000000,
                'tanggal' => now(),
                'keterangan' => 'Gotong royong & hibah swadaya warga',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.2. Pendapatan Transfer',
                'kategori' => 'Dana Desa (DD) - APBN',
                'jumlah' => 850000000,
                'realisasi' => 600000000,
                'tanggal' => now(),
                'keterangan' => 'Pencairan Dana Desa Tahap I & II',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.2. Pendapatan Transfer',
                'kategori' => 'Alokasi Dana Desa (ADD) - APBD Kabupaten',
                'jumlah' => 420000000,
                'realisasi' => 315000000,
                'tanggal' => now(),
                'keterangan' => 'Alokasi dana desa Pemkab Smart',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.2. Pendapatan Transfer',
                'kategori' => 'Bagi Hasil Pajak & Retribusi Daerah (BHPR)',
                'jumlah' => 65000000,
                'realisasi' => 48000000,
                'tanggal' => now(),
                'keterangan' => 'Bagi hasil pajak daerah Kabupaten',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.2. Pendapatan Transfer',
                'kategori' => 'Bantuan Keuangan Provinsi',
                'jumlah' => 100000000,
                'realisasi' => 100000000,
                'tanggal' => now(),
                'keterangan' => 'Banprov infrastruktur pedesaan',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pendapatan',
                'sub_kategori' => '4.3. Pendapatan Lain-Lain',
                'kategori' => 'Bunga Bank & Hasil Kerjasama Desa',
                'jumlah' => 8000000,
                'realisasi' => 6500000,
                'tanggal' => now(),
                'keterangan' => 'Jasa giro rekening desa & kerjasama',
            ],

            // ── 2. BELANJA DESA (AKUN 5 - 5 BIDANG PERMENDAGRI 20/2018) ─────
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.1. Bidang Penyelenggaraan Pemerintahan Desa',
                'kategori' => 'Siltap & Tunjangan Kepala Desa & Perangkat',
                'jumlah' => 360000000,
                'realisasi' => 270000000,
                'tanggal' => now(),
                'keterangan' => 'Penghasilan tetap Kades & Perangkat Desa',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.1. Bidang Penyelenggaraan Pemerintahan Desa',
                'kategori' => 'Operasional Kantor Desa & Administrasi',
                'jumlah' => 75000000,
                'realisasi' => 52000000,
                'tanggal' => now(),
                'keterangan' => 'ATK, listrik, internet, & operasional kantor',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.1. Bidang Penyelenggaraan Pemerintahan Desa',
                'kategori' => 'Insentif RT dan RW',
                'jumlah' => 60000000,
                'realisasi' => 45000000,
                'tanggal' => now(),
                'keterangan' => 'Honorarium Ketua RT & RW se-Desa',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.2. Bidang Pelaksanaan Pembangunan Desa',
                'kategori' => 'Pembangunan & Pavingisasi Jalan Desa',
                'jumlah' => 320000000,
                'realisasi' => 280000000,
                'tanggal' => now(),
                'keterangan' => 'Pembangunan jalan Dusun Tirta Kencana 800m',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.2. Bidang Pelaksanaan Pembangunan Desa',
                'kategori' => 'Pembangunan Drainase & Irigasi Desa',
                'jumlah' => 180000000,
                'realisasi' => 140000000,
                'tanggal' => now(),
                'keterangan' => 'Saluran drainase Dusun Lembah Surya',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.2. Bidang Pelaksanaan Pembangunan Desa',
                'kategori' => 'Pemeliharaan Sarana Air Bersih & MCK',
                'jumlah' => 65000000,
                'realisasi' => 50000000,
                'tanggal' => now(),
                'keterangan' => 'Perbaikan tandon & jaringan air bersih',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.3. Bidang Pembinaan Kemasyarakatan Desa',
                'kategori' => 'Pembinaan Karang Taruna & Olahraga',
                'jumlah' => 35000000,
                'realisasi' => 28000000,
                'tanggal' => now(),
                'keterangan' => 'Turnamen olahraga desa & kegiatan kepemudaan',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.3. Bidang Pembinaan Kemasyarakatan Desa',
                'kategori' => 'Penyelenggaraan Posyandu & Kegiatan PKK',
                'jumlah' => 45000000,
                'realisasi' => 35000000,
                'tanggal' => now(),
                'keterangan' => 'Pemberian makanan tambahan (PMT) Posyandu',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.4. Bidang Pemberdayaan Masyarakat Desa',
                'kategori' => 'Pelatihan Usaha Mikro & Digitalisasi UMKM',
                'jumlah' => 50000000,
                'realisasi' => 42000000,
                'tanggal' => now(),
                'keterangan' => 'Pelatihan kemasan produk & PIRT UMKM',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.4. Bidang Pemberdayaan Masyarakat Desa',
                'kategori' => 'Bantuan Kelompok Tani & Peternakan',
                'jumlah' => 85000000,
                'realisasi' => 70000000,
                'tanggal' => now(),
                'keterangan' => 'Pengadaan bibit unggul & pupuk organik',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'belanja',
                'sub_kategori' => '5.5. Bidang Penanggulangan Bencana & Mendesak',
                'kategori' => 'Bantuan Langsung Tunai (BLT) Dana Desa',
                'jumlah' => 144000000,
                'realisasi' => 108000000,
                'tanggal' => now(),
                'keterangan' => 'Penyaluran BLT-DD untuk KPM Ekstrem',
            ],

            // ── 3. PEMBIAYAAN DESA (AKUN 6) ──────────────────────────────────
            [
                'tahun' => $tahun,
                'tipe' => 'pembiayaan',
                'sub_kategori' => '6.1. Penerimaan Pembiayaan',
                'kategori' => 'SiLPA Tahun Anggaran Sebelumnya',
                'jumlah' => 68000000,
                'realisasi' => 68000000,
                'tanggal' => now(),
                'keterangan' => 'Sisa Lebih Perhitungan Anggaran 2025',
            ],
            [
                'tahun' => $tahun,
                'tipe' => 'pembiayaan',
                'sub_kategori' => '6.2. Pengeluaran Pembiayaan',
                'kategori' => 'Penyertaan Modal BUMDes',
                'jumlah' => 50000000,
                'realisasi' => 50000000,
                'tanggal' => now(),
                'keterangan' => 'Penambahan modal unit bisnis BUMDes',
            ],
        ];

        foreach ($items as $item) {
            Apbdes::create($item);
        }
    }
}
