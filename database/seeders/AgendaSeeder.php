<?php

namespace Database\Seeders;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $agendas = [
            [
                'judul' => 'Musyawarah Rencana Kerja Pemerintah Desa (RKPDes)',
                'deskripsi' => 'Rapat koordinasi dan pemaparan prioritas rencana kerja anggaran pembangunan desa bersama BPD, LPMD, dan tokoh masyarakat.',
                'tanggal_mulai' => Carbon::now()->addDays(3)->setTime(8, 30),
                'tanggal_selesai' => Carbon::now()->addDays(3)->setTime(12, 0),
                'lokasi' => 'Balai Pertemuan Kantor Desa',
                'kategori' => 'Musyawarah',
            ],
            [
                'judul' => 'Pelayanan Posyandu Balita & Posbindu PTM Lansia',
                'deskripsi' => 'Pemeriksaan tumbuh kembang balita, imunisasi polio/DPT, penimbangan badan, serta cek tekanan darah dan gula darah lansia gratis.',
                'tanggal_mulai' => Carbon::now()->addDays(6)->setTime(8, 0),
                'tanggal_selesai' => Carbon::now()->addDays(6)->setTime(11, 30),
                'lokasi' => 'Poskesdes Dusun Tirta Kencana',
                'kategori' => 'Kesehatan',
            ],
            [
                'judul' => 'Kerja Bakti Massal Kebersihan Saluran Irigasi & Lingkungan RT',
                'deskripsi' => 'Gotong royong pembersihan sedimentasi saluran primer irigasi pertanian serta fogging pencegahan DBD di seluruh lingkungan rukun tetangga.',
                'tanggal_mulai' => Carbon::now()->addDays(9)->setTime(6, 30),
                'tanggal_selesai' => Carbon::now()->addDays(9)->setTime(10, 0),
                'lokasi' => 'Sepanjang Saluran Irigasi Dusun Timur',
                'kategori' => 'Gotong Royong',
            ],
            [
                'judul' => 'Pelatihan Pembuatan Pupuk Organik Cair & Padat bagi Kelompok Tani',
                'deskripsi' => 'Bimbingan teknis fermentasi limbah kotoran ternak dan jerami menjadi pupuk organik berkualitas tinggi didampingi Penyuluh Pertanian Lapangan (PPL).',
                'tanggal_mulai' => Carbon::now()->addDays(14)->setTime(9, 0),
                'tanggal_selesai' => Carbon::now()->addDays(14)->setTime(14, 0),
                'lokasi' => 'Gedung Kesenian & Pelatihan Desa',
                'kategori' => 'Pelatihan',
            ],
            [
                'judul' => 'Rapat Pleno Koordinasi Pengurus RT & RW Triwulan',
                'deskripsi' => 'Evaluasi pendataan administrasi kependudukan, pengumpulan PBB-P2, serta sosialisasi sistem persuratan mandiri online.',
                'tanggal_mulai' => Carbon::now()->addDays(18)->setTime(19, 30),
                'tanggal_selesai' => Carbon::now()->addDays(18)->setTime(22, 0),
                'lokasi' => 'Ruang Rapat Kantor Desa',
                'kategori' => 'Pemerintahan',
            ],
            [
                'judul' => 'Senam Sehat Warga & Gelar Kuliner Tradisional BUMDes',
                'deskripsi' => 'Kegiatan olahraga kebugaran bersama seluruh keluarga desa yang diramaikan dengan bazar jajanan pasar binaan UMKM desa.',
                'tanggal_mulai' => Carbon::now()->addDays(22)->setTime(6, 0),
                'tanggal_selesai' => Carbon::now()->addDays(22)->setTime(9, 30),
                'lokasi' => 'Lapangan Olahraga Desa',
                'kategori' => 'Kemasyarakatan',
            ],
        ];

        foreach ($agendas as $a) {
            Agenda::updateOrCreate(
                ['judul' => $a['judul']],
                [
                    'deskripsi' => $a['deskripsi'],
                    'tanggal_mulai' => $a['tanggal_mulai'],
                    'tanggal_selesai' => $a['tanggal_selesai'],
                    'lokasi' => $a['lokasi'],
                    'kategori' => $a['kategori'],
                ]
            );
        }
    }
}
