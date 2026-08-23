<?php

namespace App\Http\Controllers;

use App\Services\PublicService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    protected PublicService $publicService;

    public function __construct(PublicService $publicService)
    {
        $this->publicService = $publicService;
    }

    public function home(): View
    {
        $data = $this->publicService->getHomePageData();
        return view('public.home', $data);
    }

    public function profil(): View
    {
        $data = $this->publicService->getProfilPageData();
        return view('public.profil', $data);
    }

    public function layanan(): View
    {
        $layananList = [
            [
                'title' => 'Surat Keterangan Domisili',
                'category' => 'Kependudukan',
                'desc' => 'Surat resmi menerangkan tempat tinggal atau domisili warga di wilayah desa.',
                'syarat' => ['Fotokopi KTP', 'Fotokopi KK', 'Surat Pengantar RT/RW'],
                'alur' => ['Pengajuan Berkas', 'Verifikasi Admin', 'Tanda Tangan Kades', 'Selesai'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'category' => 'Sosial & Pendidikan',
                'desc' => 'Surat keterangan kondisi ekonomi kurang mampu untuk keperluan sekolah atau pengobatan.',
                'syarat' => ['Fotokopi KTP & KK', 'Surat Pengantar RT/RW', 'Bukti DTKS / Kartu Perlindungan Sosial'],
                'alur' => ['Pengajuan Berkas', 'Pengecekan Data Sosial', 'Tanda Tangan Kades', 'Selesai'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Surat Keterangan Usaha (SKU)',
                'category' => 'Ekonomi & UMKM',
                'desc' => 'Surat keterangan kepemilikan dan kelayakan usaha warga di wilayah desa.',
                'syarat' => ['Fotokopi KTP & KK', 'Foto Tempat Usaha', 'Surat Pengantar RT/RW'],
                'alur' => ['Pengajuan Berkas', 'Verifikasi Lapangan', 'Tanda Tangan Kades', 'Selesai'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Surat Keterangan Kelahiran',
                'category' => 'Kependudukan',
                'desc' => 'Surat keterangan pengantar akta lahir bayi yang lahir di wilayah desa.',
                'syarat' => ['Fotokopi KTP Orang Tua & KK', 'Surat Keterangan Bidan/RS', 'Nama Bayi'],
                'alur' => ['Pengajuan Berkas', 'Verifikasi Data', 'Cetak Pengantar Akta Lahir'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Surat Keterangan Kematian',
                'category' => 'Kependudukan',
                'desc' => 'Surat resmi menerangkan peristiwa kematian warga desa.',
                'syarat' => ['Fotokopi KTP Almarhum & Pelapor', 'Fotokopi KK', 'Surat Keterangan Dokter/Bidan'],
                'alur' => ['Laporan Peristiwa', 'Verifikasi Data', 'Penerbitan Surat Kematian'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Surat Pengantar Pindah Tempat',
                'category' => 'Kependudukan',
                'desc' => 'Surat pengantar perpindahan alamat atau domisili warga ke desa/kota lain.',
                'syarat' => ['KTP & KK Asli', 'Alamat Tujuan Pindah Lengkap', 'Pasfoto 3x4'],
                'alur' => ['Pengajuan Berkas', 'Verifikasi Berkas', 'Penerbitan SKPWNI'],
                'jam' => '08.00 – 15.00 WIB',
            ],
            [
                'title' => 'Layanan Pengaduan & Aspirasi',
                'category' => 'Layanan Online',
                'desc' => 'Fasilitas penyampaian laporan kejadian, kerusakan fasilitas, atau usulan pembangunan desa.',
                'syarat' => ['NIK Warga Terdaftar', 'Foto Lokasi Kejadian (opsional)'],
                'alur' => ['Input Form Online', 'Verifikasi NIK', 'Tiket Pengaduan Otomatis', 'Tindak Lanjut Admin'],
                'jam' => '24 Jam (Portal Online)',
            ],
        ];

        $settings = $this->publicService->getHomePageData()['settings'];
        return view('public.layanan', compact('layananList', 'settings'));
    }

    public function berita(Request $request): View
    {
        $search = $request->get('q');
        $kategori = $request->get('kategori');
        $beritaList = $this->publicService->getBeritaList($search, $kategori);

        return view('public.berita.index', compact('beritaList', 'search', 'kategori'));
    }

    public function beritaShow(string $slug): View
    {
        $data = $this->publicService->getBeritaDetail($slug);
        return view('public.berita.show', $data);
    }

    public function agenda(): View
    {
        $data = $this->publicService->getAgendaPageData();
        return view('public.agenda', $data);
    }

    public function umkm(Request $request): View
    {
        $kategori = $request->get('kategori');
        $umkmList = $this->publicService->getHomePageData()['umkm'];
        return view('public.umkm.index', compact('umkmList', 'kategori'));
    }

    public function galeri(): View
    {
        $galeriList = $this->publicService->getHomePageData()['galeri'];
        return view('public.galeri', compact('galeriList'));
    }
}
