<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\User;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        $categoriesData = [
            'Pemerintahan Desa' => 'pemerintahan-desa',
            'Pembangunan & Infrastruktur' => 'pembangunan-infrastruktur',
            'Sosial & Kesehatan' => 'sosial-kesehatan',
            'Potensi & Ekonomi Desa' => 'potensi-ekonomi-desa',
            'Pengumuman Resmi' => 'pengumuman-resmi',
        ];

        $categories = [];
        foreach ($categoriesData as $catNama => $catSlug) {
            $categories[$catNama] = KategoriBerita::firstOrCreate(
                ['nama' => $catNama],
                ['slug' => $catSlug]
            );
        }

        $beritasData = [
            [
                'judul' => 'Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa Berjalan Transparan dan Tertib',
                'kategori' => 'Sosial & Kesehatan',
                'konten' => '<p>Pemerintah Desa telah sukses menyalurkan Bantuan Langsung Tunai (BLT) Dana Desa kepada Keluarga Penerima Manfaat (KPM) bertempat di Balai Desa. Seluruh proses pembagian berjalan tertib, transparan, dan disaksikan langsung oleh jajaran BPD serta Bhabinkamtibmas.</p><p>Kepala Desa menegaskan bahwa program BLT Dana Desa merupakan komitmen nyata pemerintah desa dalam memberikan perlindungan sosial dan menjaga daya beli masyarakat rentan di tengah tantangan perekonomian.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-1.jpg',
                'theme' => 'emerald',
                'views' => 245,
            ],
            [
                'judul' => 'Peningkatan Infrastruktur Jalan Usaha Tani untuk Mendukung Distribusi Panen Warga',
                'kategori' => 'Pembangunan & Infrastruktur',
                'konten' => '<p>Pembangunan rabat beton jalan usaha tani sepanjang 1,2 kilometer telah rampung dikerjakan melalui swakelola padat karya tunai desa. Infrastruktur ini menghubungkan area persawahan warga dengan jalan poros utama kecamatan.</p><p>Dengan selesainya pembangunan ini, biaya angkut hasil panen padi dan jagung dapat ditekan hingga 30%, sehingga meningkatkan efisiensi dan pendapatan petani lokal secara signifikan.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-2.jpg',
                'theme' => 'blue',
                'views' => 312,
            ],
            [
                'judul' => 'Musrenbangdes Penetapan Rencana Kerja Pemerintah Desa (RKPDes) Tahun Anggaran Berjalan',
                'kategori' => 'Pemerintahan Desa',
                'konten' => '<p>Badan Permusyawaratan Desa (BPD) bersama Pemerintah Desa menyelenggarakan Musyawarah Perencanaan Pembangunan Desa (Musrenbangdes) dalam rangka menyepakati prioritas pembangunan desa.</p><p>Fokus utama penganggaran tahun ini diarahkan pada penguatan ketahanan pangan nabati dan hewani, digitalisasi administrasi layanan mandiri, serta penanganan stunting terpadu.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-3.jpg',
                'theme' => 'indigo',
                'views' => 189,
            ],
            [
                'judul' => 'Program Posyandu Integrasi Layanan Primer (ILP) untuk Balita dan Lansia',
                'kategori' => 'Pengumuman Resmi',
                'konten' => '<p>Pemerintah Desa melalui Kader Kesehatan menyelenggarakan kegiatan Posyandu Terpadu dengan pendekatan Integrasi Layanan Primer (ILP). Layanan mencakup penimbangan balita, imunisasi dasar lengkap, cek kesehatan berkala lansia, serta pembagian makanan tambahan (PMT) bergizi.</p><p>Warga diimbau untuk selalu aktif membawa balita dan anggota keluarga lansia ke pos kesehatan desa setiap awal bulan.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-4.jpg',
                'theme' => 'cyan',
                'views' => 420,
            ],
            [
                'judul' => 'Pelatihan Digitalisasi Pemasaran Produk UMKM dan Penguatan Manajemen BUMDes',
                'kategori' => 'Potensi & Ekonomi Desa',
                'konten' => '<p>Guna memperluas jangkauan pasar komoditas unggulan desa, BUMDes menggelar workshop digitalisasi dan branding produk UMKM. Pelatihan ini menghadirkan praktisi e-commerce dan foto produk profesional.</p><p>Para pelaku usaha mikro diajarkan cara pengemasan standar higienis, pendaftaran izin edar P-IRT/NIB, serta pemanfaatan etalase online website desa.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-5.jpg',
                'theme' => 'amber',
                'views' => 275,
            ],
            [
                'judul' => 'Aksi Gotong Royong Kebersihan Lingkungan dan Penghijauan Sumber Mata Air Dusun',
                'kategori' => 'Pembangunan & Infrastruktur',
                'konten' => '<p>Ratusan warga desa bergotong royong membersihkan saluran irigasi dan menanam 500 bibit pohon keras di sekitar catchment area mata air desa. Kegiatan ini diprakarsai oleh Karang Taruna bersama perangkat dusun.</p><p>Langkah konservasi ini bertujuan menjaga debit mata air bersih tetap melimpah dan mencegah erosi pada musim penghujan.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-6.jpg',
                'theme' => 'emerald',
                'views' => 198,
            ],
            [
                'judul' => 'Penyelenggaraan Festival Budaya Tradisi Bersih Desa dan Gelar Seni Lokal',
                'kategori' => 'Potensi & Ekonomi Desa',
                'konten' => '<p>Pemerintah Desa menyelenggarakan kirab budaya bersih desa sebagai wujud syukur atas hasil panen dan kelestarian tradisi leluhur. Acara dimeriahkan dengan pameran kuliner tradisional dan pertunjukan kesenian rakyat.</p><p>Kegiatan ini sukses menarik ribuan pengunjung dari luar daerah dan mendongkrak transaksi pedagang UMKM lokal desa.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-7.jpg',
                'theme' => 'purple',
                'views' => 380,
            ],
            [
                'judul' => 'Peluncuran Aplikasi Layanan Mandiri Persuratan dan Administrasi Kependudukan Desa',
                'kategori' => 'Pemerintahan Desa',
                'konten' => '<p>Pemerintah Desa resmi meluncurkan portal layanan mandiri kependudukan berbasis website. Kini warga dapat mengajukan permohonan surat keterangan, mengecek data bansos, dan menyampaikan aspirasi secara mudah dari rumah.</p><p>Inovasi ini diharapkan mampu memangkas waktu pengurusan administrasi warga menjadi lebih transparan, akuntabel, dan hemat waktu.</p>',
                'status' => 'published',
                'cover_image' => 'berita/berita-8.jpg',
                'theme' => 'blue',
                'views' => 510,
            ],
        ];

        foreach ($beritasData as $b) {
            $cat = $categories[$b['kategori']] ?? KategoriBerita::first();

            // Generate physical image file
            ImageGenerator::createBeritaCover(
                $b['cover_image'],
                $b['judul'],
                $b['kategori'],
                $b['theme']
            );

            Berita::updateOrCreate(
                ['judul' => $b['judul']],
                [
                    'slug' => Str::slug($b['judul']),
                    'isi' => $b['konten'],
                    'cover_image' => $b['cover_image'],
                    'status' => $b['status'],
                    'user_id' => $adminId,
                    'kategori_berita_id' => $cat->id,
                    'views' => $b['views'] ?? rand(100, 450),
                ]
            );
        }
    }
}
