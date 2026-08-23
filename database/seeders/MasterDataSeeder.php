<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\GolonganDarah;
use App\Models\StatusKawin;
use App\Models\StatusTinggal;
use App\Models\Kewarganegaraan;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Dusuns (Fantasy Theme)
        $d1 = Dusun::firstOrCreate(['nama' => 'Dusun Tirta Kencana']);
        $d2 = Dusun::firstOrCreate(['nama' => 'Dusun Lembah Surya']);
        $d3 = Dusun::firstOrCreate(['nama' => 'Dusun Bintang Kejora']);
        $d4 = Dusun::firstOrCreate(['nama' => 'Dusun Rimba Lestari']);

        // 2. Seed RWs under Dusuns
        $rw1 = Rw::firstOrCreate(['dusun_id' => $d1->id, 'nomor' => '01']);
        $rw2 = Rw::firstOrCreate(['dusun_id' => $d1->id, 'nomor' => '02']);
        $rw3 = Rw::firstOrCreate(['dusun_id' => $d2->id, 'nomor' => '03']);
        $rw4 = Rw::firstOrCreate(['dusun_id' => $d3->id, 'nomor' => '04']);

        // 3. Seed RTs under RWs
        $rt1 = Rt::firstOrCreate(['rw_id' => $rw1->id, 'nomor' => '01']);
        $rt2 = Rt::firstOrCreate(['rw_id' => $rw1->id, 'nomor' => '02']);
        $rt3 = Rt::firstOrCreate(['rw_id' => $rw2->id, 'nomor' => '01']);
        $rt4 = Rt::firstOrCreate(['rw_id' => $rw3->id, 'nomor' => '01']);
        $rt5 = Rt::firstOrCreate(['rw_id' => $rw4->id, 'nomor' => '01']);

        // 4. Lookups
        $agama = Agama::first()->id ?? 1;
        $pendidikan = Pendidikan::first()->id ?? 1;
        $pekerjaan = Pekerjaan::first()->id ?? 1;
        $goldar = GolonganDarah::first()->id ?? 1;
        $kawin = StatusKawin::first()->id ?? 1;
        $tinggal = StatusTinggal::first()->id ?? 1;
        $warga = Kewarganegaraan::first()->id ?? 1;

        // 5. Seed a Family Card
        $kk1 = KartuKeluarga::firstOrCreate(
            ['no_kk' => '3507111212000001'],
            [
                'alamat' => 'Jl. Lembah Cahaya No. 10',
                'dusun_id' => $d1->id,
                'rw_id' => $rw1->id,
                'rt_id' => $rt1->id,
            ]
        );

        // 6. Seed a Resident
        $p1 = Penduduk::firstOrCreate(
            ['nik' => '3507111212900001'],
            [
                'no_kk' => $kk1->no_kk,
                'nama' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Candraloka',
                'tanggal_lahir' => '1990-12-12',
                'agama_id' => $agama,
                'status_kawin_id' => $kawin,
                'pendidikan_id' => $pendidikan,
                'pekerjaan_id' => $pekerjaan,
                'alamat' => 'Jl. Lembah Cahaya No. 10',
                'dusun_id' => $d1->id,
                'rw_id' => $rw1->id,
                'rt_id' => $rt1->id,
                'nomor_hp' => '08123456789',
                'email' => 'budi@gmail.com',
                'foto' => 'penduduk/foto-pria-1.jpg',
                'status_tinggal_id' => $tinggal,
                'kewarganegaraan_id' => $warga,
                'golongan_darah_id' => $goldar,
            ]
        );

        // 7. Update Kepala Keluarga on Family Card
        $kk1->update(['kepala_keluarga_nik' => $p1->nik]);

        // 8. Seed another Resident (female)
        $p2 = Penduduk::firstOrCreate(
            ['nik' => '3507111212900002'],
            [
                'no_kk' => $kk1->no_kk,
                'nama' => 'Siti Aminah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Nirwana Raya',
                'tanggal_lahir' => '1993-05-15',
                'agama_id' => $agama,
                'status_kawin_id' => $kawin,
                'pendidikan_id' => $pendidikan,
                'pekerjaan_id' => $pekerjaan,
                'alamat' => 'Jl. Lembah Cahaya No. 10',
                'dusun_id' => $d1->id,
                'rw_id' => $rw1->id,
                'rt_id' => $rt1->id,
                'nomor_hp' => '08129876543',
                'email' => 'siti@gmail.com',
                'foto' => 'penduduk/foto-wanita-1.jpg',
                'status_tinggal_id' => $tinggal,
                'kewarganegaraan_id' => $warga,
                'golongan_darah_id' => $goldar,
            ]
        );
    }
}
