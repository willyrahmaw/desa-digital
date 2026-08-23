<?php

namespace Database\Seeders;

use App\Models\BannerHero;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class BannerHeroSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'judul' => 'Selamat Datang di Portal Resmi Desa Digital',
                'subjudul' => 'Pusat Informasi, Keterbukaan Publik & Pelayanan Kependudukan Modern',
                'gambar' => 'banner_hero/banner-1.jpg',
                'tag' => 'PROFIL DESA',
                'link_url' => '/profil',
                'button_text' => 'Jelajahi Profil Desa',
                'theme' => 'blue',
                'urutan' => 1,
            ],
            [
                'judul' => 'Transparansi Anggaran & Akuntabilitas APBDes',
                'subjudul' => 'Pengelolaan Keuangan Terbuka Sesuai Permendagri No. 20 Tahun 2018 demi Kesejahteraan Bersama',
                'gambar' => 'banner_hero/banner-2.jpg',
                'tag' => 'TRANSPARANSI PUBLIK',
                'link_url' => '#apbdes',
                'button_text' => 'Lihat Rincian APBDes',
                'theme' => 'emerald',
                'urutan' => 2,
            ],
            [
                'judul' => 'Pengaduan & Pelayanan Warga Cepat Berbasis NIK',
                'subjudul' => 'Sampaikan Aspirasi, Laporan Fasilitas Publik & Administrasi Persuratan Secara Online 24 Jam',
                'gambar' => 'banner_hero/banner-3.jpg',
                'tag' => 'LAYANAN MANDIRI',
                'link_url' => '/layanan',
                'button_text' => 'Layanan Administrasi Warga',
                'theme' => 'cyan',
                'urutan' => 3,
            ],
            [
                'judul' => 'Pemberdayaan Ekonomi & Produk Unggulan UMKM',
                'subjudul' => 'Mendukung Kemandirian Ekonomi Warga dan Unit Usaha BUMDes Menuju Desa Mandiri',
                'gambar' => 'banner_hero/banner-4.jpg',
                'tag' => 'POTENSI EKONOMI',
                'link_url' => '/umkm',
                'button_text' => 'Jelajahi Produk UMKM',
                'theme' => 'amber',
                'urutan' => 4,
            ],
        ];

        foreach ($banners as $b) {
            ImageGenerator::createBanner(
                $b['gambar'],
                $b['judul'],
                $b['subjudul'],
                $b['tag'],
                $b['theme']
            );

            BannerHero::updateOrCreate(
                ['judul' => $b['judul']],
                [
                    'subjudul' => $b['subjudul'],
                    'gambar' => $b['gambar'],
                    'tag' => $b['tag'],
                    'link_url' => $b['link_url'],
                    'button_text' => $b['button_text'],
                    'urutan' => $b['urutan'],
                    'status_aktif' => true,
                ]
            );
        }
    }
}
