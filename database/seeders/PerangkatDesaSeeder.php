<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\PerangkatDesa;
use App\Models\User;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;

class PerangkatDesaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        $perangkatList = [
            [
                'nama' => 'Ki Ageng Suryakencana, S.Sos',
                'nip' => '19750815 200212 1 003',
                'jabatan_nama' => 'Kepala Desa',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kades.jpg',
            ],
            [
                'nama' => 'Damar Prameswara, S.Kom',
                'nip' => '19820310 200801 1 008',
                'jabatan_nama' => 'Sekretaris Desa',
                'gender' => 'male',
                'foto' => 'perangkat_desa/sekdes.jpg',
            ],
            [
                'nama' => 'Sri Wahyuni, S.E',
                'nip' => '19880521 201101 2 012',
                'jabatan_nama' => 'Kepala Urusan Keuangan',
                'gender' => 'female',
                'foto' => 'perangkat_desa/kaur-keuangan.jpg',
            ],
            [
                'nama' => 'Eko Prasetyo, S.Kom',
                'nip' => '19901104 201402 1 005',
                'jabatan_nama' => 'Kepala Urusan Umum & Perencanaan',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kaur-umum.jpg',
            ],
            [
                'nama' => 'Budi Santoso, S.AP',
                'nip' => '19850719 200903 1 007',
                'jabatan_nama' => 'Kepala Urusan Pemerintahan',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kaur-pemerintahan.jpg',
            ],
            [
                'nama' => 'Siti Rohani, S.Pd',
                'nip' => '19920418 201503 2 009',
                'jabatan_nama' => 'Kepala Seksi Kesejahteraan',
                'gender' => 'female',
                'foto' => 'perangkat_desa/kasi-kesra.jpg',
            ],
            [
                'nama' => 'Ahmad Fauzi, S.H',
                'nip' => '19890925 201201 1 011',
                'jabatan_nama' => 'Kepala Seksi Pelayanan',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kasi-pelayanan.jpg',
            ],
            [
                'nama' => 'Suwandi',
                'nip' => '19830612 201604 1 015',
                'jabatan_nama' => 'Kepala Dusun Tirta Kencana',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kadus-1.jpg',
            ],
            [
                'nama' => 'Slamet Raharjo',
                'nip' => '19861024 201701 1 018',
                'jabatan_nama' => 'Kepala Dusun Lembah Surya',
                'gender' => 'male',
                'foto' => 'perangkat_desa/kadus-2.jpg',
            ],
        ];

        foreach ($perangkatList as $p) {
            $jabatan = Jabatan::firstOrCreate(['nama' => $p['jabatan_nama']]);

            // Generate physical portrait photo
            ImageGenerator::createPerangkatPortrait(
                $p['foto'],
                $p['nama'],
                $p['jabatan_nama'],
                $p['gender']
            );

            PerangkatDesa::updateOrCreate(
                ['nip' => $p['nip']],
                [
                    'nama' => $p['nama'],
                    'jabatan_id' => $jabatan->id,
                    'user_id' => $adminId,
                    'foto' => $p['foto'],
                    'status_aktif' => true,
                ]
            );
        }
    }
}
