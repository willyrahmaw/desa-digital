<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\GolonganDarah;
use App\Models\StatusKawin;
use App\Models\StatusTinggal;
use App\Models\Kewarganegaraan;
use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Lookups
        $agamas = ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'];
        foreach ($agamas as $a) {
            Agama::firstOrCreate(['nama' => $a]);
        }

        $pendidikans = [
            'Tidak/Belum Sekolah',
            'Belum Tamat SD/Sederajat',
            'Tamat SD/Sederajat',
            'SLTP/Sederajat',
            'SLTA/Sederajat',
            'Diploma I/II',
            'Diploma III/Sarjana Muda',
            'Diploma IV/Strata I',
            'Strata II',
            'Strata III'
        ];
        foreach ($pendidikans as $p) {
            Pendidikan::firstOrCreate(['nama' => $p]);
        }

        $pekerjaans = [
            'Belum/Tidak Bekerja',
            'Mengurus Rumah Tangga',
            'Pelajar/Mahasiswa',
            'Pensiunan',
            'Pewira Swasta/Wiraswasta',
            'Karyawan Swasta',
            'Karyawan BUMN/BUMD',
            'Pegawai Negeri Sipil (PNS)',
            'TNI',
            'POLRI',
            'Petani/Pekebun',
            'Nelayan',
            'Buruh Harian Lepas',
            'Lainnya'
        ];
        foreach ($pekerjaans as $pe) {
            Pekerjaan::firstOrCreate(['nama' => $pe]);
        }

        $golongans = ['A', 'B', 'AB', 'O', 'Tidak Tahu'];
        foreach ($golongans as $g) {
            GolonganDarah::firstOrCreate(['nama' => $g]);
        }

        $kawins = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
        foreach ($kawins as $k) {
            StatusKawin::firstOrCreate(['nama' => $k]);
        }

        $tinggals = ['Tetap', 'Kontrak/Sewa', 'Sementara', 'Pendatang'];
        foreach ($tinggals as $t) {
            StatusTinggal::firstOrCreate(['nama' => $t]);
        }

        $wargas = ['WNI', 'WNA'];
        foreach ($wargas as $w) {
            Kewarganegaraan::firstOrCreate(['nama' => $w]);
        }

        $jabatans = [
            'Kepala Desa',
            'Sekretaris Desa',
            'Kepala Urusan Keuangan',
            'Kepala Urusan Umum & Perencanaan',
            'Kepala Urusan Pemerintahan',
            'Kepala Seksi Kesejahteraan',
            'Kepala Seksi Pelayanan',
            'Kepala Dusun',
            'Staff Desa'
        ];
        foreach ($jabatans as $j) {
            Jabatan::firstOrCreate(['nama' => $j]);
        }

        // 2. Seed Permissions
        $permissions = [
            'dashboard' => 'Melihat Dashboard & Statistik',
            'manage-master' => 'Mengelola Data Master (Dusun, RT, RW, Lookups)',
            'manage-penduduk' => 'Mengelola Data Penduduk & Kartu Keluarga',
            'manage-sosial' => 'Mengelola Data Sosial & Bansos',
            'manage-surat' => 'Mengelola Template & Pelayanan Surat',
            'manage-pengaduan' => 'Mengelola Pengaduan Warga',
            'manage-berita' => 'Mengelola Berita & Kategori',
            'manage-agenda' => 'Mengelola Agenda Desa',
            'manage-galeri' => 'Mengelola Galeri Foto/Video',
            'manage-umkm' => 'Mengelola Profil UMKM & Produk',
            'manage-bumdes' => 'Mengelola Unit Usaha BUMDes',
            'manage-apbdes' => 'Mengelola Anggaran APBDes',
            'manage-users' => 'Mengelola Akun Operator & Perangkat',
            'view-logs' => 'Melihat Audit Trail & Log Aktivitas',
            'manage-settings' => 'Mengatur Profil Desa & Konfigurasi'
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $label) {
            $permissionModels[$name] = Permission::firstOrCreate([
                'name' => $name,
                'label' => $label
            ]);
        }

        // 3. Seed Roles
        $roles = [
            'super-admin' => 'Super Admin',
            'admin-desa' => 'Admin Desa',
            'operator' => 'Operator',
            'sekretaris' => 'Sekretaris Desa',
            'kepala-desa' => 'Kepala Desa',
            'staff' => 'Staff Administrasi'
        ];

        $roleModels = [];
        foreach ($roles as $name => $label) {
            $roleModels[$name] = Role::firstOrCreate([
                'name' => $name,
                'label' => $label
            ]);
        }

        // 4. Assign Permissions to Roles
        // Super Admin gets all permissions
        $roleModels['super-admin']->permissions()->sync(array_column($permissionModels, 'id'));

        // Admin Desa gets all except system settings maybe, or gets all too
        $roleModels['admin-desa']->permissions()->sync(array_column($permissionModels, 'id'));

        // Operator gets core data input capabilities
        $operatorPermissions = [
            $permissionModels['dashboard']->id,
            $permissionModels['manage-penduduk']->id,
            $permissionModels['manage-sosial']->id,
            $permissionModels['manage-surat']->id,
            $permissionModels['manage-pengaduan']->id,
            $permissionModels['manage-umkm']->id,
        ];
        $roleModels['operator']->permissions()->sync($operatorPermissions);

        // Sekretaris gets master data, penduduk, and surat signing
        $sekretarisPermissions = [
            $permissionModels['dashboard']->id,
            $permissionModels['manage-master']->id,
            $permissionModels['manage-penduduk']->id,
            $permissionModels['manage-sosial']->id,
            $permissionModels['manage-surat']->id,
            $permissionModels['manage-apbdes']->id,
        ];
        $roleModels['sekretaris']->permissions()->sync($sekretarisPermissions);

        // Kepala Desa gets dashboard viewing, surat sign permissions (read), pengaduan, APBDes
        $kadesPermissions = [
            $permissionModels['dashboard']->id,
            $permissionModels['manage-sosial']->id,
            $permissionModels['manage-surat']->id,
            $permissionModels['manage-pengaduan']->id,
            $permissionModels['manage-apbdes']->id,
            $permissionModels['view-logs']->id,
        ];
        $roleModels['kepala-desa']->permissions()->sync($kadesPermissions);

        // Staff gets public facing updates (berita, agenda, galeri, umkm)
        $staffPermissions = [
            $permissionModels['dashboard']->id,
            $permissionModels['manage-berita']->id,
            $permissionModels['manage-agenda']->id,
            $permissionModels['manage-galeri']->id,
            $permissionModels['manage-umkm']->id,
            $permissionModels['manage-bumdes']->id,
        ];
        $roleModels['staff']->permissions()->sync($staffPermissions);

        // 5. Create Super Admin User
        User::firstOrCreate(
            ['email' => 'admin@desa.go.id'],
            [
                'name' => 'Super Administrator E-Desa',
                'password' => Hash::make('password'),
                'role_id' => $roleModels['super-admin']->id,
                'status_aktif' => true,
            ]
        );

        $this->call([
            PengaturanSeeder::class,
            MasterDataSeeder::class,
            WargaStatistikSeeder::class,
            TemplateSuratSeeder::class,
            KlasifikasiSuratSeeder::class,
            BannerHeroSeeder::class,
            BeritaSeeder::class,
            AgendaSeeder::class,
            GaleriSeeder::class,
            UmkmSeeder::class,
            BumdesSeeder::class,
            PerangkatDesaSeeder::class,
            ApbdesSeeder::class,
        ]);
    }
}
