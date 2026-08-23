<?php

namespace App\Interfaces;

use App\Models\Penduduk;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\FotoGaleri;
use App\Models\UmkmProduk;
use App\Models\PerangkatDesa;
use App\Models\Pengaduan;
use App\Models\BannerHero;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PublicRepositoryInterface
{
    public function getBannerHeroList(): Collection;
    public function getSettings(): array;
    public function getQuickStats(): array;
    public function getDemographicsData(): array;
    public function getApbdesSummary(int $tahun = 0): array;
    public function getPerangkatDesaList(): Collection;
    public function getLatestBerita(int $limit = 6): Collection;
    public function getPaginatedBerita(int $perPage = 9, ?string $search = null, ?string $kategori = null): LengthAwarePaginator;
    public function getBeritaBySlug(string $slug): ?Berita;
    public function getRelatedBerita(int $currentId, int $limit = 4): Collection;
    public function getLatestAgenda(int $limit = 6): Collection;
    public function getAllAgenda(): Collection;
    public function getGaleriList(int $limit = 12): Collection;
    public function getUmkmList(?string $kategori = null, int $limit = 12): Collection;
    public function findPendudukByNik(string $nik): ?Penduduk;
    public function createPengaduan(array $data): Pengaduan;
    public function findPengaduanByTiketAndNik(string $nomorTiket, string $nik): ?Pengaduan;
}
