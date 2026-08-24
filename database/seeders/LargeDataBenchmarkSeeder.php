<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\Penduduk;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\StatusKawin;
use App\Models\StatusTinggal;
use App\Models\Kewarganegaraan;
use App\Models\GolonganDarah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeDataBenchmarkSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Memulai seeding skala besar (ratusan Dusun & ribuan Penduduk)...');

        // Disable foreign key constraints during bulk insert for speed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Lookup IDs
        $agamaId = Agama::first()->id ?? 1;
        $pendidikanId = Pendidikan::first()->id ?? 1;
        $pekerjaanId = Pekerjaan::first()->id ?? 1;
        $statusKawinId = StatusKawin::first()->id ?? 1;
        $statusTinggalId = StatusTinggal::first()->id ?? 1;
        $kewarganegaraanId = Kewarganegaraan::first()->id ?? 1;
        $golonganDarahId = GolonganDarah::first()->id ?? 1;

        // 1. Seed 100 Dusun with unique names
        $dusunData = [];
        $dusunNames = [
            'Krajan', 'Mulyo', 'Sukamaju', 'Sidomulyo', 'Karanganyar', 'Wanasari', 
            'Jatirejo', 'Sumberagung', 'Kembang', 'Tegalsari', 'Margosari', 'Bantarsari',
            'Pule', 'Kaliwungu', 'Candi', 'Rawa', 'Tanjung', 'Beringin', 'Harapan', 'Nusa'
        ];

        $batchTag = rand(100, 999);

        for ($i = 1; $i <= 100; $i++) {
            $baseName = $dusunNames[($i - 1) % count($dusunNames)];
            $nama = "Dusun Benchmark " . $baseName . " #" . $batchTag . "-" . $i;
            
            $dusunData[] = [
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Dusun::insert($dusunData);
        $dusunIds = Dusun::where('nama', 'like', "Dusun Benchmark%#{$batchTag}%")->pluck('id')->toArray();
        $this->command->info('Berhasil membuat 100 Dusun.');

        // 2. Seed 250 RW
        $rwData = [];
        foreach ($dusunIds as $dusunId) {
            $countRw = rand(2, 3);
            for ($r = 1; $r <= $countRw; $r++) {
                $rwData[] = [
                    'dusun_id' => $dusunId,
                    'nomor' => sprintf('%02d', $r),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Rw::insert($rwData);
        $rws = Rw::whereIn('dusun_id', $dusunIds)->get();
        $this->command->info('Berhasil membuat ' . count($rws) . ' RW.');

        // 3. Seed ~600 RT
        $rtData = [];
        foreach ($rws as $rw) {
            $countRt = rand(2, 3);
            for ($t = 1; $t <= $countRt; $t++) {
                $rtData[] = [
                    'rw_id' => $rw->id,
                    'nomor' => sprintf('%02d', $t),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Rt::insert($rtData);
        $rwIds = $rws->pluck('id')->toArray();
        $rts = Rt::whereIn('rw_id', $rwIds)->with('rw')->get();
        $this->command->info('Berhasil membuat ' . count($rts) . ' RT.');

        // 4. Seed 3,000 Penduduk
        $firstNamesMale = ['Budi', 'Ahmad', 'Siti', 'Eko', 'Agus', 'Dwi', 'Rudi', 'Hendra', 'Yusuf', 'Irfan', 'Doni', 'Bambang', 'Slamet', 'Rahmat', 'Fajar', 'Teguh', 'Dimas', 'Bayu', 'Rizky', 'Heri'];
        $firstNamesFemale = ['Sri', 'Siti', 'Dewi', 'Nurul', 'Rina', 'Indah', 'Lestari', 'Maya', 'Fitri', 'Kartika', 'Wati', 'Tini', 'Anisa', 'Ratna', 'Dian', 'Eka', 'Yulia', 'Ningsih', 'Sari', 'Rini'];
        $lastNames = ['Santoso', 'Pratama', 'Wibowo', 'Saputra', 'Setiawan', 'Hidayat', 'Kurniawan', 'Nugroho', 'Wijaya', 'Utomo', 'Haryanto', 'Suryono', 'Ramadhan', 'Purnomo', 'Fahmi'];

        $kotaLahir = ['Semarang', 'Surakarta', 'Yogyakarta', 'Magelang', 'Banyumas', 'Cilacap', 'Kudus', 'Jepara', 'Pati', 'Kebumen'];

        $pendudukChunk = [];
        $totalPenduduk = 3000;

        for ($p = 1; $p <= $totalPenduduk; $p++) {
            $jk = ($p % 2 == 0) ? 'L' : 'P';
            $firstName = ($jk == 'L') 
                ? $firstNamesMale[array_rand($firstNamesMale)] 
                : $firstNamesFemale[array_rand($firstNamesFemale)];
            $lastName = $lastNames[array_rand($lastNames)];
            $namaLengkap = $firstName . ' ' . $lastName;

            $rt = $rts->random();
            $dusunId = $rt->rw->dusun_id ?? $dusunIds[0];

            $pendudukChunk[] = [
                'nik' => sprintf('00000102%08d', $p),
                'nama' => $namaLengkap,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $kotaLahir[array_rand($kotaLahir)],
                'tanggal_lahir' => date('Y-m-d', strtotime('-' . rand(18, 65) . ' years -' . rand(1, 365) . ' days')),
                'agama_id' => $agamaId,
                'pendidikan_id' => $pendidikanId,
                'pekerjaan_id' => $pekerjaanId,
                'status_kawin_id' => $statusKawinId,
                'status_tinggal_id' => $statusTinggalId,
                'kewarganegaraan_id' => $kewarganegaraanId,
                'golongan_darah_id' => $golonganDarahId,
                'alamat' => 'Jl. Desa No. ' . rand(1, 150),
                'dusun_id' => $dusunId,
                'rw_id' => $rt->rw_id,
                'rt_id' => $rt->id,
                'nomor_hp' => '08' . rand(1000000000, 9999999999),
                'email' => 'warga' . $batchTag . '_' . $p . '@edesademo.id',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($pendudukChunk) >= 500) {
                Penduduk::insert($pendudukChunk);
                $pendudukChunk = [];
            }
        }

        if (!empty($pendudukChunk)) {
            Penduduk::insert($pendudukChunk);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("Selesai! Berhasil membuat 100 Dusun, " . count($rws) . " RW, " . count($rts) . " RT, dan {$totalPenduduk} data warga penduduk!");
    }
}
