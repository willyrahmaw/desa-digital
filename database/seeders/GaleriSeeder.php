<?php

namespace Database\Seeders;

use App\Models\AlbumGaleri;
use App\Models\FotoGaleri;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'nama' => 'Pembangunan Infrastruktur Desa',
                'deskripsi' => 'Dokumentasi pelaksanaan proyek fisik pengaspalan jalan, rabat beton, dan normalisasi irigasi desa.',
                'theme' => 'blue',
                'fotos' => [
                    [
                        'judul' => 'Pengaspalan Hotmix Jalan Poros Dusun Tirta Kencana',
                        'deskripsi' => 'Peningkatan sarana transportasi umum untuk menunjang aktivitas perekonomian warga.',
                        'file_path' => 'galeri/infrastruktur-1.jpg',
                    ],
                    [
                        'judul' => 'Pembangunan Saluran Drainase & Gorong-Gorong RT 03',
                        'deskripsi' => 'Pencegahan genangan air saat curah hujan tinggi di pemukiman warga.',
                        'file_path' => 'galeri/infrastruktur-2.jpg',
                    ],
                    [
                        'judul' => 'Pembangunan Jembatan Penghubung Antar Dusun',
                        'deskripsi' => 'Akses penghubung vital yang mempermudah mobilitas antar dusun dan petani desa.',
                        'file_path' => 'galeri/infrastruktur-3.jpg',
                    ],
                ]
            ],
            [
                'nama' => 'Pelayanan Masyarakat & Kesehatan',
                'deskripsi' => 'Dokumentasi kegiatan posyandu, penyaluran bansos, dan loket pelayanan administrasi kependudukan.',
                'theme' => 'emerald',
                'fotos' => [
                    [
                        'judul' => 'Pemeriksaan Kesehatan Berkala Lansia di Posbindu',
                        'deskripsi' => 'Pemberian vitamin, cek tensi darah, dan konsultasi kesehatan gratis oleh tenaga medis desa.',
                        'file_path' => 'galeri/kesehatan-1.jpg',
                    ],
                    [
                        'judul' => 'Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa',
                        'deskripsi' => 'Penyerahan bantuan tunai kepada warga lansia dan keluarga kurang mampu secara transparan.',
                        'file_path' => 'galeri/kesehatan-2.jpg',
                    ],
                    [
                        'judul' => 'Posyandu Integrasi Layanan Primer (ILP) Balita',
                        'deskripsi' => 'Penimbangan balita dan pemberian makanan tambahan (PMT) bergizi mencegah stunting.',
                        'file_path' => 'galeri/kesehatan-3.jpg',
                    ],
                ]
            ],
            [
                'nama' => 'Seni Budaya & Kemasyarakatan',
                'deskripsi' => 'Dokumentasi perayaan hari kemerdekaan, gotong royong warga, dan pagelaran seni lokal.',
                'theme' => 'purple',
                'fotos' => [
                    [
                        'judul' => 'Kerja Bakti Bersih Desa Menjelang Musim Tanam',
                        'deskripsi' => 'Semangat gotong royong antar warga membersihkan lingkungan dan fasilitas umum.',
                        'file_path' => 'galeri/masyarakat-1.jpg',
                    ],
                    [
                        'judul' => 'Festival Seni Tradisi & Bazar UMKM BUMDes',
                        'deskripsi' => 'Gelar budaya dan pameran produk kerajinan tangan hasil karya perajin desa.',
                        'file_path' => 'galeri/masyarakat-2.jpg',
                    ],
                    [
                        'judul' => 'Pawai Budaya & Kirab Tradisi Bersih Dusun',
                        'deskripsi' => 'Karnaval pakaian adat Nusantara dan atraksi kesenian tradisional pemuda desa.',
                        'file_path' => 'galeri/masyarakat-3.jpg',
                    ],
                ]
            ],
            [
                'nama' => 'Potensi Wisata & Alam Desa',
                'deskripsi' => 'Dokumentasi keindahan lanskap persawahan, sumber mata air, dan destinasi wisata desa.',
                'theme' => 'cyan',
                'fotos' => [
                    [
                        'judul' => 'Ekowisata Persawahan Organik Dusun Tirta Kencana',
                        'deskripsi' => 'Hamparan persawahan bertingkat dengan udara segar lereng perbukitan desa.',
                        'file_path' => 'galeri/wisata-1.jpg',
                    ],
                    [
                        'judul' => 'Sumber Mata Air Alami Watu Lumbung',
                        'deskripsi' => 'Mata air jernih sumber kehidupan warga dan destinasi wisata pemandian alam.',
                        'file_path' => 'galeri/wisata-2.jpg',
                    ],
                ]
            ],
        ];

        foreach ($albums as $alb) {
            $album = AlbumGaleri::firstOrCreate(
                ['nama' => $alb['nama']],
                ['deskripsi' => $alb['deskripsi']]
            );

            foreach ($alb['fotos'] as $f) {
                // Generate physical gallery image file
                ImageGenerator::createGaleriPhoto(
                    $f['file_path'],
                    $f['judul'],
                    $alb['nama'],
                    $alb['theme']
                );

                FotoGaleri::updateOrCreate(
                    [
                        'album_id' => $album->id,
                        'judul' => $f['judul']
                    ],
                    [
                        'file_path' => $f['file_path'],
                        'deskripsi' => $f['deskripsi'],
                    ]
                );
            }
        }
    }
}
