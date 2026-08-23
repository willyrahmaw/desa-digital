<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        // Generate transparent official fantasy village emblem logo
        $logoPath = ImageGenerator::createLogoDesa('pengaturan/logo-desa.png', 'CANDRALOKA');

        $settings = [
            // Profil Desa Fantasy
            ['key' => 'nama_desa', 'value' => 'Desa Candraloka', 'group' => 'profil', 'description' => 'Nama Resmi Pemerintahan Desa'],
            ['key' => 'kecamatan', 'value' => 'Astraguna', 'group' => 'profil', 'description' => 'Nama Kecamatan'],
            ['key' => 'nama_kecamatan', 'value' => 'Astraguna', 'group' => 'profil', 'description' => 'Nama Kecamatan'],
            ['key' => 'kabupaten', 'value' => 'Nirwana Raya', 'group' => 'profil', 'description' => 'Nama Kabupaten / Kota'],
            ['key' => 'nama_kabupaten', 'value' => 'Nirwana Raya', 'group' => 'profil', 'description' => 'Nama Kabupaten / Kota'],
            ['key' => 'provinsi', 'value' => 'Fantasia Nusantara', 'group' => 'profil', 'description' => 'Nama Provinsi'],
            ['key' => 'kode_pos', 'value' => '99881', 'group' => 'profil', 'description' => 'Kode Pos Wilayah'],
            ['key' => 'motto_desa', 'value' => 'Harmoni Alam, Cahaya Kemakmuran, dan Kearifan Bersama', 'group' => 'profil', 'description' => 'Motto / Slogan Desa'],
            ['key' => 'alamat_kantor', 'value' => 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya', 'group' => 'profil', 'description' => 'Alamat Lengkap Balai Desa'],
            ['key' => 'telepon_desa', 'value' => '+62 811-7788-9900', 'group' => 'profil', 'description' => 'Nomor Telepon Hotline Kantor Desa'],
            ['key' => 'telp_desa', 'value' => '+62 811-7788-9900', 'group' => 'profil', 'description' => 'Nomor Telepon Hotline Kantor Desa'],
            ['key' => 'email_desa', 'value' => 'kontak@candraloka.desa.id', 'group' => 'profil', 'description' => 'Alamat Email Resmi Kantor Desa'],
            ['key' => 'nama_kades', 'value' => 'Ki Ageng Suryakencana, S.Sos', 'group' => 'profil', 'description' => 'Nama Kepala Desa'],
            ['key' => 'nama_sekdes', 'value' => 'Damar Prameswara, S.Kom', 'group' => 'profil', 'description' => 'Nama Sekretaris Desa'],
            ['key' => 'logo_desa', 'value' => $logoPath, 'group' => 'profil', 'description' => 'File Lambang / Logo Resmi Desa'],
            ['key' => 'visi', 'value' => 'Terwujudnya Desa Candraloka yang Berkelimpahan, Mandiri, Lestari, dan Berperadaban Maju Melalui Tata Kelola Pemerintahan yang Transparan Berbasis Digital.', 'group' => 'profil', 'description' => 'Visi Pemerintahan Desa'],
            ['key' => 'misi', 'value' => "1. Mewujudkan pelayanan pemerintahan desa berbasis digital yang cepat, tanggap, dan akuntabel.\n2. Mengoptimalkan kelestarian alam dan agrowisata berbasis kearifan lokal.\n3. Memperkuat kemandirian ekonomi rakyat melalui BUMDes Mahakarya dan ekosistem UMKM unggulan.\n4. Meningkatkan derajat kesehatan masyarakat serta perlindungan sosial yang merata dan berkelanjutan.", 'group' => 'profil', 'description' => 'Misi Pemerintahan Desa'],

            // KOP Surat Resmi Fantasy Identity
            ['key' => 'kop_line_1', 'value' => 'PEMERINTAH KABUPATEN NIRWANA RAYA', 'group' => 'surat', 'description' => 'KOP Surat Baris 1 (Pemerintah Kabupaten)'],
            ['key' => 'kop_line_2', 'value' => 'KECAMATAN ASTRAGUNA', 'group' => 'surat', 'description' => 'KOP Surat Baris 2 (Kecamatan)'],
            ['key' => 'kop_line_3', 'value' => 'PEMERINTAH DESA CANDRALOKA', 'group' => 'surat', 'description' => 'KOP Surat Baris 3 (Pemerintah Desa)'],
            ['key' => 'kop_alamat', 'value' => 'Kompleks Praja Mandiri No. 99, Dusun Tirta Kencana, Kec. Astraguna, Kab. Nirwana Raya 99881', 'group' => 'surat', 'description' => 'KOP Surat Alamat Kantor'],
            ['key' => 'kop_telepon', 'value' => '+62 811-7788-9900', 'group' => 'surat', 'description' => 'KOP Surat Telepon'],
            ['key' => 'kop_email', 'value' => 'kontak@candraloka.desa.id', 'group' => 'surat', 'description' => 'KOP Surat Email'],
            ['key' => 'kop_website', 'value' => 'candraloka.desa.id', 'group' => 'surat', 'description' => 'KOP Surat Website'],
            ['key' => 'kop_kontak', 'value' => 'Website: https://candraloka.desa.id | Email: kontak@candraloka.desa.id | Telp: +62 811-7788-9900', 'group' => 'surat', 'description' => 'KOP Surat Baris Kontak Terpadu'],
        ];

        foreach ($settings as $s) {
            Pengaturan::updateOrCreate(
                ['key' => $s['key']],
                [
                    'value' => $s['value'],
                    'group' => $s['group'],
                    'description' => $s['description'],
                ]
            );
        }
    }
}
