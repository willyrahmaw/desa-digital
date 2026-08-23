<?php

namespace Database\Seeders;

use App\Models\Agama;
use App\Models\BumdesUnit;
use App\Models\DataSosial;
use App\Models\Dusun;
use App\Models\GolonganDarah;
use App\Models\KartuKeluarga;
use App\Models\Kewarganegaraan;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\StatusKawin;
use App\Models\StatusTinggal;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Models\UmkmPelaku;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\Helpers\ImageGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WargaStatistikSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::create([
            'name' => 'Super Administrator E-Desa',
            'email' => 'admin@desa.go.id',
            'password' => bcrypt('password'),
            'status_aktif' => true,
        ]);

        // 1. Ensure Dusuns, RWs, RTs (Fantasy Theme)
        $dusunNames = ['Dusun Tirta Kencana', 'Dusun Lembah Surya', 'Dusun Bintang Kejora', 'Dusun Rimba Lestari'];
        $dusuns = [];
        foreach ($dusunNames as $idx => $dName) {
            $dusuns[$idx] = Dusun::firstOrCreate(['nama' => $dName]);
        }

        $rts = [];
        foreach ($dusuns as $dIdx => $dusun) {
            $rw = Rw::firstOrCreate(['dusun_id' => $dusun->id, 'nomor' => sprintf('%02d', $dIdx + 1)]);
            for ($i = 1; $i <= 2; $i++) {
                $rt = Rt::firstOrCreate(['rw_id' => $rw->id, 'nomor' => sprintf('%02d', $i)]);
                $rts[] = [
                    'dusun_id' => $dusun->id,
                    'rw_id' => $rw->id,
                    'rt_id' => $rt->id,
                ];
            }
        }

        // 2. Fetch Lookups
        $agamas = Agama::all();
        $pendidikans = Pendidikan::all();
        $pekerjaans = Pekerjaan::all();
        $goldars = GolonganDarah::all();
        $kawins = StatusKawin::all();
        $tinggals = StatusTinggal::all();
        $wargas = Kewarganegaraan::all();

        if ($agamas->isEmpty() || $pendidikans->isEmpty() || $pekerjaans->isEmpty()) {
            $this->call(DatabaseSeeder::class);
            $agamas = Agama::all();
            $pendidikans = Pendidikan::all();
            $pekerjaans = Pekerjaan::all();
            $goldars = GolonganDarah::all();
            $kawins = StatusKawin::all();
            $tinggals = StatusTinggal::all();
            $wargas = Kewarganegaraan::all();
        }

        // 3. Resident Datasets (Male & Female names)
        $priaNames = [
            'Budi Santoso', 'Bambang Wijaya', 'Joko Widodo', 'Ahmad Dahlan', 'Edi Sugianto',
            'Tri Mulyono', 'Rudi Hermawan', 'Hadi Sucipto', 'Hendra Kurniawan', 'Anton Prasetyo',
            'Slamet Riyadi', 'Agus Kurniadi', 'Supriadi', 'Eko Yulianto', 'Totok Raharjo',
            'Bayu Setiawan', 'Rizky Pratama', 'Arif Budiman', 'Dimas Anggara', 'Irfan Bachdim',
            'Dedi Mulyadi', 'Satria Nusantara', 'Lukman Hakim', 'Teguh Utomo', 'Surya Saputra',
            'Arief Muhammad', 'Bintang Pramudya', 'Rahmat Hidayat', 'Fajar Ramadhan', 'Agung Laksono'
        ];

        $wanitaNames = [
            'Siti Aminah', 'Dewi Sartika', 'Sri Wahyuni', 'Ratna Sari', 'Rina Nose',
            'Endang Lestari', 'Yuni Shara', 'Maya Kusuma', 'Nining Meida', 'Ani Yudhoyono',
            'Fitri Salhuteru', 'Kartini Indah', 'Nurul Hidayah', 'Tari Wulandari', 'Reni Anggraini',
            'Siska Wati', 'Eka Putri', 'Dian Sastro', 'Rosiana Silalahi', 'Gita Gutawa',
            'Ayu Tingting', 'Melly Goeslaw', 'Desy Ratnasari', 'Maia Estianty', 'Lia Marlina',
            'Wulan Guritno', 'Titi Kamal', 'Zaskia Gotik', 'Inul Daratista', 'Via Vallen'
        ];

        // Pre-generate 10 Male and 10 Female ID Photos
        for ($i = 1; $i <= 10; $i++) {
            $pName = $priaNames[$i - 1];
            $wName = $wanitaNames[$i - 1];
            ImageGenerator::createPendudukPhoto("penduduk/foto-pria-{$i}.jpg", $pName, 'male', $i % 2 === 0 ? 'blue' : 'red');
            ImageGenerator::createPendudukPhoto("penduduk/foto-wanita-{$i}.jpg", $wName, 'female', $i % 2 === 0 ? 'red' : 'blue');
        }

        // 4. Create Kartu Keluarga & Penduduk
        $createdPenduduks = [];

        for ($kkIndex = 1; $kkIndex <= 25; $kkIndex++) {
            $noKk = sprintf('350711%02d0000%02d', 12, $kkIndex);
            $location = $rts[$kkIndex % count($rts)];

            $kk = KartuKeluarga::firstOrCreate(
                ['no_kk' => $noKk],
                [
                    'alamat' => 'Jl. Merdeka No. ' . ($kkIndex * 3),
                    'dusun_id' => $location['dusun_id'],
                    'rw_id' => $location['rw_id'],
                    'rt_id' => $location['rt_id'],
                ]
            );

            // Head of Family (Male)
            $priaName = $priaNames[($kkIndex - 1) % count($priaNames)];
            $nikSuami = sprintf('35071112%02d9000%02d', 12, $kkIndex);
            $priaBirthDate = Carbon::now()->subYears(rand(28, 65))->subDays(rand(1, 300))->toDateString();
            $photoIndexPria = (($kkIndex - 1) % 10) + 1;

            $pria = Penduduk::firstOrCreate(
                ['nik' => $nikSuami],
                [
                    'no_kk' => $kk->no_kk,
                    'nama' => $priaName,
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Candraloka',
                    'tanggal_lahir' => $priaBirthDate,
                    'agama_id' => $agamas->random()->id,
                    'status_kawin_id' => $kawins->where('nama', 'Kawin')->first()->id ?? $kawins->first()->id,
                    'pendidikan_id' => $pendidikans->random()->id,
                    'pekerjaan_id' => $pekerjaans->random()->id,
                    'alamat' => $kk->alamat,
                    'dusun_id' => $location['dusun_id'],
                    'rw_id' => $location['rw_id'],
                    'rt_id' => $location['rt_id'],
                    'nomor_hp' => '081' . rand(10000000, 99999999),
                    'email' => Str::slug($priaName) . '@gmail.com',
                    'foto' => "penduduk/foto-pria-{$photoIndexPria}.jpg",
                    'status_tinggal_id' => $tinggals->first()->id ?? 1,
                    'kewarganegaraan_id' => $wargas->first()->id ?? 1,
                    'golongan_darah_id' => $goldars->random()->id,
                    'created_at' => Carbon::now()->subMonths(rand(0, 11)),
                ]
            );

            $kk->update(['kepala_keluarga_nik' => $pria->nik]);
            $createdPenduduks[] = $pria;

            // Wife (Female)
            $wanitaName = $wanitaNames[($kkIndex - 1) % count($wanitaNames)];
            $nikIstri = sprintf('35071120%02d9000%02d', 12, $kkIndex);
            $wanitaBirthDate = Carbon::now()->subYears(rand(25, 60))->subDays(rand(1, 300))->toDateString();
            $photoIndexWanita = (($kkIndex - 1) % 10) + 1;

            $wanita = Penduduk::firstOrCreate(
                ['nik' => $nikIstri],
                [
                    'no_kk' => $kk->no_kk,
                    'nama' => $wanitaName,
                    'jenis_kelamin' => 'P',
                    'tempat_lahir' => 'Nirwana Raya',
                    'tanggal_lahir' => $wanitaBirthDate,
                    'agama_id' => $agamas->random()->id,
                    'status_kawin_id' => $kawins->where('nama', 'Kawin')->first()->id ?? $kawins->first()->id,
                    'pendidikan_id' => $pendidikans->random()->id,
                    'pekerjaan_id' => $pekerjaans->where('nama', 'Mengurus Rumah Tangga')->first()->id ?? $pekerjaans->random()->id,
                    'alamat' => $kk->alamat,
                    'dusun_id' => $location['dusun_id'],
                    'rw_id' => $location['rw_id'],
                    'rt_id' => $location['rt_id'],
                    'nomor_hp' => '082' . rand(10000000, 99999999),
                    'email' => Str::slug($wanitaName) . '@gmail.com',
                    'foto' => "penduduk/foto-wanita-{$photoIndexWanita}.jpg",
                    'status_tinggal_id' => $tinggals->first()->id ?? 1,
                    'kewarganegaraan_id' => $wargas->first()->id ?? 1,
                    'golongan_darah_id' => $goldars->random()->id,
                    'created_at' => Carbon::now()->subMonths(rand(0, 11)),
                ]
            );
            $createdPenduduks[] = $wanita;
        }

        // 5. Create Data Sosial (Welfare & Desil 1-10)
        foreach ($createdPenduduks as $i => $penduduk) {
            if ($i % 2 === 0) { // seed for half the residents
                $desil = ($i % 10) + 1; // 1 to 10
                $isMiskin = $desil <= 3;
                $layakSktm = $desil <= 4;

                DataSosial::updateOrCreate(
                    ['penduduk_nik' => $penduduk->nik],
                    [
                        'dtks' => $isMiskin,
                        'pkh' => $desil <= 2,
                        'bpnt' => $desil <= 3,
                        'pbi' => $desil <= 4,
                        'rtlh' => $desil === 1,
                        'desil' => $desil,
                        'layak_sktm' => $layakSktm,
                        'tanggal_verifikasi' => Carbon::now()->subDays(rand(5, 120)),
                        'verifikator_id' => $user->id,
                        'keterangan' => 'Hasil verifikasi data sosial desa ter-update (Desil ' . $desil . ')',
                    ]
                );
            }
        }

        // 6. Create Historical Surat Applications (2026)
        $templates = TemplateSurat::all();
        if ($templates->isNotEmpty()) {
            // Seed ~35 letters across January to July 2026
            for ($m = 1; $m <= 7; $m++) {
                $countInMonth = rand(4, 8);
                for ($j = 1; $j <= $countInMonth; $j++) {
                    $penduduk = $createdPenduduks[array_rand($createdPenduduks)];
                    $template = $templates->random();
                    $date = Carbon::create(2026, $m, rand(1, 28));
                    $status = rand(1, 10) > 2 ? 'approved' : (rand(1, 2) === 1 ? 'pending' : 'rejected');

                    Surat::updateOrCreate(
                        ['nomor_surat' => sprintf('470/%03d/Desa/%s/2026', ($m * 10) + $j, $m)],
                        [
                            'uuid' => (string) Str::uuid(),
                            'jenis_surat' => $template->jenis_surat ?? $template->nama,
                            'penduduk_nik' => $penduduk->nik,
                            'template_id' => $template->id,
                            'status' => $status,
                            'tanggal_terbit' => $date->toDateString(),
                            'petugas_id' => $user->id,
                            'meta_data' => ['keperluan' => 'Permohonan ' . ($template->nama)],
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]
                    );
                }
            }
        }

        // 7. Seed Pengaduan Warga with attachments
        $pengaduanTitles = [
            [
                'judul' => 'Lampu Penerangan Jalan Tirta Kencana Padam',
                'lampiran' => 'pengaduan/lampiran-lampu-penerangan.jpg',
                'kategori' => 'Infrastruktur',
            ],
            [
                'judul' => 'Saluran Irigasi Sawah Tersumbat Endapan Lumpur',
                'lampiran' => 'pengaduan/lampiran-irigasi-tersumbat.jpg',
                'kategori' => 'Infrastruktur',
            ],
            [
                'judul' => 'Permohonan Perbaikan Jalan Berlubang RT 02 Dusun Lembah Surya',
                'lampiran' => 'pengaduan/lampiran-jalan-rusak.jpg',
                'kategori' => 'Infrastruktur',
            ],
            [
                'judul' => 'Sampah Menumpuk di Depan Balai RW 03',
                'lampiran' => 'pengaduan/lampiran-sampah.jpg',
                'kategori' => 'Kebersihan',
            ],
            [
                'judul' => 'Layanan Permohonan Kartu Keluarga Perlu Dipercepat',
                'lampiran' => null,
                'kategori' => 'Pelayanan',
            ],
            [
                'judul' => 'Pohon Rindang Berbahaya di Tepi Jalan Utama',
                'lampiran' => 'pengaduan/lampiran-pohon-tumbang.jpg',
                'kategori' => 'Ketertiban',
            ],
            [
                'judul' => 'Genangan Air Masuk ke Halaman Rumah Saat Hujan Deras',
                'lampiran' => 'pengaduan/lampiran-drainase-banjir.jpg',
                'kategori' => 'Infrastruktur',
            ],
            [
                'judul' => 'Usulan Pembentukan Posyandu Remaja dan Lansia Terpadu',
                'lampiran' => null,
                'kategori' => 'Kesehatan',
            ],
        ];

        foreach ($pengaduanTitles as $idx => $pData) {
            $penduduk = $createdPenduduks[array_rand($createdPenduduks)];
            $status = match($idx % 3) {
                0 => 'resolved',
                1 => 'process',
                2 => 'pending',
            };

            if ($pData['lampiran']) {
                ImageGenerator::createGaleriPhoto(
                    $pData['lampiran'],
                    $pData['judul'],
                    'LAMPIRAN PENGADUAN',
                    'amber'
                );
            }

            Pengaduan::updateOrCreate(
                ['nomor_tiket' => 'TKT-' . date('Ymd') . '-' . sprintf('%04d', $idx + 1)],
                [
                    'pelapor_nik' => $penduduk->nik,
                    'judul' => $pData['judul'],
                    'kategori' => $pData['kategori'],
                    'isi' => 'Laporan pengaduan mengenai ' . strtolower($pData['judul']) . ' mohon segera ditindaklanjuti oleh petugas pemerintah desa terkait.',
                    'status' => $status,
                    'lampiran' => $pData['lampiran'],
                    'balasan' => $status === 'resolved' ? 'Terima kasih atas laporan warga. Petugas lapangan telah menindaklanjuti dan menyelesaikan kendala tersebut.' : ($status === 'process' ? 'Laporan sedang dalam verifikasi dan penanganan tim teknis desa.' : null),
                    'created_at' => Carbon::now()->subDays(rand(1, 45)),
                ]
            );
        }
    }
}
