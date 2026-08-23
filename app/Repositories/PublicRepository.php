<?php

namespace App\Repositories;

use App\Interfaces\PublicRepositoryInterface;
use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\Dusun;
use App\Models\Apbdes;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\FotoGaleri;
use App\Models\UmkmProduk;
use App\Models\PerangkatDesa;
use App\Models\Pengaduan;
use App\Models\BannerHero;
use App\Models\Pengaturan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublicRepository implements PublicRepositoryInterface
{
    public function getBannerHeroList(): Collection
    {
        return BannerHero::where('status_aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();
    }

    public function getSettings(): array
    {
        return Pengaturan::pluck('value', 'key')->toArray();
    }

    public function getQuickStats(): array
    {
        return [
            'total_penduduk' => Penduduk::count(),
            'total_kk' => KartuKeluarga::count(),
            'total_rt' => Rt::count(),
            'total_rw' => Rw::count(),
            'total_dusun' => Dusun::count(),
        ];
    }

    public function getDemographicsData(): array
    {
        $totalPenduduk = Penduduk::count();

        // Gender breakdown
        $countL = Penduduk::where('jenis_kelamin', 'L')->count();
        $countP = Penduduk::where('jenis_kelamin', 'P')->count();
        $gender = [
            'L' => $countL,
            'P' => $countP,
            'persen_L' => $totalPenduduk > 0 ? round(($countL / $totalPenduduk) * 100, 1) : 0,
            'persen_P' => $totalPenduduk > 0 ? round(($countP / $totalPenduduk) * 100, 1) : 0,
        ];

        // Dusun breakdown via join with complete metrics
        $dusunData = Penduduk::join('rt', 'penduduk.rt_id', '=', 'rt.id')
            ->join('rw', 'rt.rw_id', '=', 'rw.id')
            ->join('dusun', 'rw.dusun_id', '=', 'dusun.id')
            ->selectRaw('dusun.id as id, dusun.nama as nama_dusun, count(penduduk.nik) as total')
            ->groupBy('dusun.id', 'dusun.nama')
            ->orderByRaw('count(penduduk.nik) DESC')
            ->pluck('total', 'nama_dusun')
            ->toArray();

        $dusunDetails = Dusun::withCount(['rws', 'rts'])
            ->get()
            ->map(function ($dusun) use ($totalPenduduk, $dusunData) {
                $total = $dusunData[$dusun->nama] ?? Penduduk::whereHas('rt.rw', function ($q) use ($dusun) {
                    $q->where('dusun_id', $dusun->id);
                })->count();

                $persen = $totalPenduduk > 0 ? round(($total / $totalPenduduk) * 100, 1) : 0;

                return [
                    'id' => $dusun->id,
                    'nama' => $dusun->nama,
                    'total_rw' => $dusun->rws_count ?? 0,
                    'total_rt' => $dusun->rts_count ?? 0,
                    'total_penduduk' => $total,
                    'persen' => $persen,
                ];
            })
            ->sortByDesc('total_penduduk')
            ->values();

        // Agama breakdown
        $agamaData = Penduduk::join('agama', 'penduduk.agama_id', '=', 'agama.id')
            ->selectRaw('agama.nama as nama_agama, count(*) as total')
            ->groupBy('agama.id', 'agama.nama')
            ->pluck('total', 'nama_agama')
            ->toArray();

        // Pekerjaan breakdown
        $pekerjaanData = Penduduk::join('pekerjaan', 'penduduk.pekerjaan_id', '=', 'pekerjaan.id')
            ->selectRaw('pekerjaan.nama as nama_pekerjaan, count(*) as total')
            ->groupBy('pekerjaan.id', 'pekerjaan.nama')
            ->orderByRaw('count(*) DESC')
            ->limit(8)
            ->pluck('total', 'nama_pekerjaan')
            ->toArray();

        // Pendidikan breakdown
        $pendidikanData = Penduduk::join('pendidikan', 'penduduk.pendidikan_id', '=', 'pendidikan.id')
            ->selectRaw('pendidikan.nama as nama_pendidikan, count(*) as total')
            ->groupBy('pendidikan.id', 'pendidikan.nama')
            ->pluck('total', 'nama_pendidikan')
            ->toArray();

        return [
            'total_penduduk' => $totalPenduduk,
            'gender' => $gender,
            'dusun' => $dusunData,
            'dusun_details' => $dusunDetails,
            'agama' => $agamaData,
            'pekerjaan' => $pekerjaanData,
            'pendidikan' => $pendidikanData,
        ];
    }

    public function getApbdesSummary(int $tahun = 0): array
    {
        if ($tahun === 0) {
            $tahun = (int)date('Y');
        }

        $items = Apbdes::where('tahun', $tahun)->get();

        $revenue = $items->where('tipe', 'pendapatan');
        $spending = $items->where('tipe', 'belanja');
        $financing = $items->where('tipe', 'pembiayaan');

        $totPendapatanAnggaran = $revenue->sum('jumlah');
        $totPendapatanRealisasi = $revenue->sum('realisasi');

        $totBelanjaAnggaran = $spending->sum('jumlah');
        $totBelanjaRealisasi = $spending->sum('realisasi');

        $totPembiayaanAnggaran = $financing->sum('jumlah');
        $totPembiayaanRealisasi = $financing->sum('realisasi');

        // Group by sub_kategori for detailed tables
        $groupedPendapatan = $revenue->groupBy('sub_kategori')->map(function ($subItems, $subName) {
            $anggaran = $subItems->sum('jumlah');
            $realisasi = $subItems->sum('realisasi');
            $persen = $anggaran > 0 ? min(100, round(($realisasi / $anggaran) * 100, 1)) : 0;
            return [
                'name' => $subName ?: 'Pendapatan Lain-lain',
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'sisa' => max(0, $anggaran - $realisasi),
                'persen' => $persen,
                'items' => $subItems,
            ];
        });

        $groupedBelanja = $spending->groupBy('sub_kategori')->map(function ($subItems, $subName) {
            $anggaran = $subItems->sum('jumlah');
            $realisasi = $subItems->sum('realisasi');
            $persen = $anggaran > 0 ? min(100, round(($realisasi / $anggaran) * 100, 1)) : 0;
            return [
                'name' => $subName ?: 'Belanja Lainnya',
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'sisa' => max(0, $anggaran - $realisasi),
                'persen' => $persen,
                'items' => $subItems,
            ];
        });

        $groupedPembiayaan = $financing->groupBy('sub_kategori')->map(function ($subItems, $subName) {
            $anggaran = $subItems->sum('jumlah');
            $realisasi = $subItems->sum('realisasi');
            $persen = $anggaran > 0 ? min(100, round(($realisasi / $anggaran) * 100, 1)) : 0;
            return [
                'name' => $subName ?: 'Pembiayaan Lainnya',
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'sisa' => max(0, $anggaran - $realisasi),
                'persen' => $persen,
                'items' => $subItems,
            ];
        });

        $availableYears = Apbdes::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        if (empty($availableYears)) {
            $availableYears = [$tahun];
        }

        return [
            'tahun' => $tahun,
            'available_years' => $availableYears,
            'pendapatan_anggaran' => $totPendapatanAnggaran,
            'pendapatan_realisasi' => $totPendapatanRealisasi,
            'pendapatan_persen' => $totPendapatanAnggaran > 0 ? round(($totPendapatanRealisasi / $totPendapatanAnggaran) * 100, 1) : 0,
            
            'belanja_anggaran' => $totBelanjaAnggaran,
            'belanja_realisasi' => $totBelanjaRealisasi,
            'belanja_persen' => $totBelanjaAnggaran > 0 ? round(($totBelanjaRealisasi / $totBelanjaAnggaran) * 100, 1) : 0,

            'pembiayaan_anggaran' => $totPembiayaanAnggaran,
            'pembiayaan_realisasi' => $totPembiayaanRealisasi,
            'pembiayaan_persen' => $totPembiayaanAnggaran > 0 ? round(($totPembiayaanRealisasi / $totPembiayaanAnggaran) * 100, 1) : 0,

            'surplus_defisit' => $totPendapatanRealisasi - $totBelanjaRealisasi,
            'grouped_pendapatan' => $groupedPendapatan,
            'grouped_belanja' => $groupedBelanja,
            'grouped_pembiayaan' => $groupedPembiayaan,
            'items' => $items,
        ];
    }

    public function getPerangkatDesaList(): Collection
    {
        return PerangkatDesa::with('jabatan')
            ->where('status_aktif', true)
            ->get();
    }

    public function getLatestBerita(int $limit = 6): Collection
    {
        return Berita::with('kategori')
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    public function getPaginatedBerita(int $perPage = 9, ?string $search = null, ?string $kategori = null): LengthAwarePaginator
    {
        $query = Berita::with('kategori')->latest('created_at');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        if (!empty($kategori)) {
            $query->whereHas('kategori', function ($q) use ($kategori) {
                $q->where('slug', $kategori)->orWhere('nama', $kategori);
            });
        }

        return $query->paginate($perPage);
    }

    public function getBeritaBySlug(string $slug): ?Berita
    {
        return Berita::with('kategori')->where('slug', $slug)->first();
    }

    public function getRelatedBerita(int $currentId, int $limit = 4): Collection
    {
        return Berita::with('kategori')
            ->where('id', '!=', $currentId)
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    public function getLatestAgenda(int $limit = 6): Collection
    {
        return Agenda::orderBy('tanggal_mulai', 'desc')
            ->take($limit)
            ->get();
    }

    public function getAllAgenda(): Collection
    {
        return Agenda::orderBy('tanggal_mulai', 'asc')->get();
    }

    public function getGaleriList(int $limit = 12): Collection
    {
        return FotoGaleri::latest()
            ->take($limit)
            ->get();
    }

    public function getUmkmList(?string $kategori = null, int $limit = 12): Collection
    {
        $query = UmkmProduk::with('pelaku');
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }
        return $query->latest()->take($limit)->get();
    }

    public function findPendudukByNik(string $nik): ?Penduduk
    {
        return Penduduk::with(['rt.rw.dusun'])->where('nik', $nik)->first();
    }

    public function createPengaduan(array $data): Pengaduan
    {
        return Pengaduan::create($data);
    }

    public function findPengaduanByTiketAndNik(string $nomorTiket, string $nik): ?Pengaduan
    {
        return Pengaduan::where('nomor_tiket', $nomorTiket)
            ->where('pelapor_nik', $nik)
            ->first();
    }
}
