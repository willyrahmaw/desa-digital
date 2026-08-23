<?php

namespace Database\Seeders;

use App\Models\UmkmKategori;
use App\Models\UmkmPelaku;
use App\Models\UmkmProduk;
use App\Models\User;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class UmkmSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        $kategoris = [
            'Makanan & Minuman Olahan' => 'makanan-minuman',
            'Kerajinan Tangan & Kriya' => 'kerajinan-kriya',
            'Pertanian & Hasil Bumi' => 'pertanian-hasil-bumi',
            'Fashion & Tekstil Tradisional' => 'fashion-tekstil',
        ];

        $kategoriModels = [];
        foreach ($kategoris as $nama => $slug) {
            $kategoriModels[$nama] = UmkmKategori::firstOrCreate(
                ['nama' => $nama],
                ['slug' => $slug]
            );
        }

        $pelakus = [
            [
                'nama' => 'Ibu Siti Aminah (Dapur Berkah)',
                'no_hp' => '081234567891',
                'alamat' => 'RT 02 RW 01, Dusun Tirta Kencana',
                'produks' => [
                    [
                        'nama' => 'Keripik Singkong Balado Renyah Asli Desa',
                        'deskripsi' => 'Keripik singkong gurih renyah dengan bumbu cabai asli pilihan tanpa bahan pengawet.',
                        'harga' => 15000,
                        'kategori' => 'Makanan & Minuman Olahan',
                        'foto' => 'umkm/keripik-singkong.jpg',
                        'theme' => 'amber',
                    ],
                    [
                        'nama' => 'Sambal Bawang Kemasan Botol Higienis',
                        'deskripsi' => 'Sambal uleg tradisional dengan rempah lokal pedas mantap siap santap.',
                        'harga' => 25000,
                        'kategori' => 'Makanan & Minuman Olahan',
                        'foto' => 'umkm/sambal-bawang.jpg',
                        'theme' => 'amber',
                    ]
                ]
            ],
            [
                'nama' => 'Pak Joko Waluyo (Karya Bambu Lestari)',
                'no_hp' => '081234567892',
                'alamat' => 'RT 04 RW 02, Dusun Lembah Surya',
                'produks' => [
                    [
                        'nama' => 'Besek & Keranjang Anyaman Bambu Alami',
                        'deskripsi' => 'Kerajinan anyaman bambu ramah lingkungan untuk wadah hantaran, souvenir, dan perabot rumah tangga.',
                        'harga' => 35000,
                        'kategori' => 'Kerajinan Tangan & Kriya',
                        'foto' => 'umkm/anyaman-bambu.jpg',
                        'theme' => 'cyan',
                    ],
                    [
                        'nama' => 'Tudung Saji Bambu Klasik Anti Lalat',
                        'deskripsi' => 'Penutup makanan dari serat bambu halus yang estetis dan tahan lama.',
                        'harga' => 60000,
                        'kategori' => 'Kerajinan Tangan & Kriya',
                        'foto' => 'umkm/tudung-saji.jpg',
                        'theme' => 'cyan',
                    ]
                ]
            ],
            [
                'nama' => 'Kelompok Tani Makmur (Pak Hendra)',
                'no_hp' => '081234567893',
                'alamat' => 'RT 01 RW 03, Dusun Bintang Kejora',
                'produks' => [
                    [
                        'nama' => 'Kopi Robusta Petik Merah Lereng Desa (250g)',
                        'deskripsi' => 'Biji kopi robusta pilihan yang ditanam di ketinggian lereng pegunungan desa, di-roasting dengan standar specialty.',
                        'harga' => 45000,
                        'kategori' => 'Pertanian & Hasil Bumi',
                        'foto' => 'umkm/kopi-robusta.jpg',
                        'theme' => 'purple',
                    ],
                    [
                        'nama' => 'Madu Murni Nektar Bunga Hutan Desa (500ml)',
                        'deskripsi' => 'Madu lebah liar alami murni 100% tanpa campuran gula, berkhasiat menjaga imunitas dan kesehatan keluarga.',
                        'harga' => 85000,
                        'kategori' => 'Pertanian & Hasil Bumi',
                        'foto' => 'umkm/madu-murni.jpg',
                        'theme' => 'amber',
                    ],
                    [
                        'nama' => 'Beras Merah Organik Bebas Pestisida (5kg)',
                        'deskripsi' => 'Beras merah pulen dari bibit lokal unggulan yang dibudidayakan secara organik ramah lingkungan.',
                        'harga' => 75000,
                        'kategori' => 'Pertanian & Hasil Bumi',
                        'foto' => 'umkm/beras-organik.jpg',
                        'theme' => 'emerald',
                    ]
                ]
            ],
            [
                'nama' => 'Sanggar Batik Melati Desa (Ibu Kartini)',
                'no_hp' => '081234567894',
                'alamat' => 'RT 03 RW 01, Dusun Tirta Kencana',
                'produks' => [
                    [
                        'nama' => 'Kain Batik Tulis Motif Daun & Padi Desa',
                        'deskripsi' => 'Kain batik cap dan tulis kombinasi dengan corak filosofis khas agraris kemakmuran desa.',
                        'harga' => 180000,
                        'kategori' => 'Fashion & Tekstil Tradisional',
                        'foto' => 'umkm/batik-desa.jpg',
                        'theme' => 'indigo',
                    ]
                ]
            ]
        ];

        foreach ($pelakus as $pel) {
            $pelaku = UmkmPelaku::firstOrCreate(
                ['nama' => $pel['nama']],
                [
                    'no_hp' => $pel['no_hp'],
                    'alamat' => $pel['alamat'],
                    'user_id' => $adminId,
                ]
            );

            foreach ($pel['produks'] as $p) {
                $kat = $kategoriModels[$p['kategori']] ?? UmkmKategori::first();

                // Generate physical UMKM product image file
                ImageGenerator::createUmkmPhoto(
                    $p['foto'],
                    $p['nama'],
                    $p['kategori'],
                    $p['harga'],
                    $p['theme']
                );

                UmkmProduk::updateOrCreate(
                    [
                        'pelaku_id' => $pelaku->id,
                        'nama' => $p['nama']
                    ],
                    [
                        'deskripsi' => $p['deskripsi'],
                        'harga' => $p['harga'],
                        'foto' => $p['foto'],
                        'kategori_id' => $kat->id,
                        'whatsapp' => $pel['no_hp'],
                    ]
                );
            }
        }
    }
}
