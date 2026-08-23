<?php

namespace App\Services;

use App\Interfaces\PublicRepositoryInterface;
use App\Models\Penduduk;
use App\Models\Berita;
use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublicService
{
    protected PublicRepositoryInterface $publicRepository;

    public function __construct(PublicRepositoryInterface $publicRepository)
    {
        $this->publicRepository = $publicRepository;
    }

    public function getHomePageData(): array
    {
        $settings = $this->publicRepository->getSettings();
        $bannerHeroes = $this->publicRepository->getBannerHeroList();
        $latestBerita = $this->publicRepository->getLatestBerita(6);

        $heroSlides = collect();

        if ($bannerHeroes->isNotEmpty()) {
            foreach ($bannerHeroes as $b) {
                $link = $b->link_url ?: route('public.profil');
                if (str_starts_with($link, '/')) {
                    $link = url($link);
                }

                $heroSlides->push([
                    'id' => $b->id,
                    'type' => 'banner',
                    'judul' => $b->judul,
                    'subjudul' => $b->subjudul,
                    'gambar' => $b->gambar,
                    'kategori' => $b->tag ?: 'INFORMASI DESA',
                    'link' => $link,
                    'button_text' => $b->button_text ?: 'Jelajahi Layanan',
                ]);
            }
        } else {
            // Fallback identity slide if table is empty
            $heroSlides->push([
                'type' => 'identity',
                'judul' => 'Selamat Datang di Portal Resmi ' . ($settings['nama_desa'] ?? 'Desa Digital'),
                'subjudul' => $settings['motto_desa'] ?? 'Pusat Informasi, Transparansi Publik, dan Pelayanan Kependudukan Desa Digital',
                'gambar' => null,
                'kategori' => 'PROFIL DESA',
                'link' => route('public.profil'),
                'button_text' => 'Jelajahi Profil Desa',
            ]);
        }

        return [
            'hero_slides' => $heroSlides,
            'settings' => $settings,
            'stats' => $this->publicRepository->getQuickStats(),
            'demographics' => $this->publicRepository->getDemographicsData(),
            'apbdes' => $this->publicRepository->getApbdesSummary(),
            'perangkat' => $this->publicRepository->getPerangkatDesaList(),
            'berita' => $latestBerita,
            'agenda' => $this->publicRepository->getLatestAgenda(4),
            'umkm' => $this->publicRepository->getUmkmList(null, 6),
            'galeri' => $this->publicRepository->getGaleriList(6),
        ];
    }

    public function getProfilPageData(): array
    {
        return [
            'perangkat' => $this->publicRepository->getPerangkatDesaList(),
            'stats' => $this->publicRepository->getQuickStats(),
            'settings' => $this->publicRepository->getSettings(),
            'demographics' => $this->publicRepository->getDemographicsData(),
        ];
    }

    public function getAgendaPageData(): array
    {
        return [
            'agenda' => $this->publicRepository->getAllAgenda(),
            'settings' => $this->publicRepository->getSettings(),
        ];
    }

    public function getBeritaList(?string $search = null, ?string $kategori = null): LengthAwarePaginator
    {
        return $this->publicRepository->getPaginatedBerita(9, $search, $kategori);
    }

    public function getBeritaDetail(string $slug): array
    {
        $berita = $this->publicRepository->getBeritaBySlug($slug);
        if (!$berita) {
            abort(404, 'Berita tidak ditemukan');
        }

        $related = $this->publicRepository->getRelatedBerita($berita->id, 4);

        return [
            'berita' => $berita,
            'related' => $related,
        ];
    }

    public function validateNik(string $nik): array
    {
        $penduduk = $this->publicRepository->findPendudukByNik($nik);

        if (!$penduduk) {
            return [
                'success' => false,
                'message' => 'NIK tidak terdaftar sebagai penduduk desa. Pengaduan tidak dapat dikirim.',
            ];
        }

        // Mask name and address for privacy protection (UU PDP compliance)
        $namaParts = explode(' ', $penduduk->nama);
        $maskedNama = implode(' ', array_map(function($part) {
            $len = mb_strlen($part);
            if ($len <= 2) return $part;
            return mb_substr($part, 0, 2) . str_repeat('*', max(1, $len - 2));
        }, $namaParts));

        return [
            'success' => true,
            'data' => [
                'nik' => $penduduk->nik,
                'nama' => $maskedNama,
                'alamat' => $penduduk->alamat ? (mb_substr($penduduk->alamat, 0, 8) . '***') : 'Alamat Desa Terdaftar',
                'rt' => $penduduk->rt->nomor_rt ?? '-',
                'rw' => $penduduk->rt->rw->nomor_rw ?? '-',
                'dusun' => $penduduk->rt->rw->dusun->nama ?? '-',
            ]
        ];
    }

    public function submitPengaduan(array $data, array $fotoPaths = []): array
    {
        $nikValidation = $this->validateNik($data['nik']);
        if (!$nikValidation['success']) {
            return $nikValidation;
        }

        $ticketYear = date('Y');
        $randomSequence = str_pad((string)rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $nomorTiket = "PGD-{$ticketYear}-{$randomSequence}";

        $pengaduanPayload = [
            'nomor_tiket' => $nomorTiket,
            'pelapor_nik' => $data['nik'],
            'telepon' => $data['telepon'],
            'email' => $data['email'] ?? null,
            'kategori' => $data['kategori'] ?? 'Umum',
            'judul' => $data['judul'],
            'isi' => $data['isi'],
            'lokasi' => $data['lokasi'] ?? null,
            'lampiran' => json_encode($fotoPaths),
            'status' => 'pending',
        ];

        $pengaduan = $this->publicRepository->createPengaduan($pengaduanPayload);

        return [
            'success' => true,
            'nomor_tiket' => $nomorTiket,
            'message' => "Pengaduan berhasil dikirim dengan Nomor Tiket: {$nomorTiket}",
            'pengaduan' => $pengaduan,
        ];
    }

    public function trackPengaduan(string $nomorTiket, string $nik): array
    {
        $pengaduan = $this->publicRepository->findPengaduanByTiketAndNik($nomorTiket, $nik);

        if (!$pengaduan) {
            return [
                'success' => false,
                'message' => 'Pengaduan dengan Nomor Tiket dan NIK tersebut tidak ditemukan.',
            ];
        }

        return [
            'success' => true,
            'data' => $pengaduan,
        ];
    }
}
