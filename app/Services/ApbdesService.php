<?php

namespace App\Services;

use App\Interfaces\ApbdesRepositoryInterface;
use App\Models\Apbdes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ApbdesService
{
    protected ApbdesRepositoryInterface $apbdesRepository;

    public function __construct(ApbdesRepositoryInterface $apbdesRepository)
    {
        $this->apbdesRepository = $apbdesRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', string $kategori = '', int $tahun = 0): LengthAwarePaginator
    {
        $query = Apbdes::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('kategori', 'like', "%{$search}%")
                  ->orWhere('sub_kategori', 'like', "%{$search}%");
            });
        }

        if (!empty($kategori)) {
            $query->where('tipe', strtolower($kategori));
        }

        if ($tahun > 0) {
            $query->where('tahun', $tahun);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): Apbdes
    {
        $payload = [
            'tahun' => $data['tahun'] ?? date('Y'),
            'tipe' => strtolower($data['kategori'] ?? 'pendapatan'),
            'sub_kategori' => $data['sub_kategori'] ?? null,
            'kategori' => $data['nama_item'] ?? $data['kategori'] ?? 'Item APBDes',
            'jumlah' => $data['anggaran'] ?? $data['jumlah'] ?? 0,
            'realisasi' => $data['realisasi'] ?? 0,
            'tanggal' => now()->toDateString(),
            'keterangan' => $data['keterangan'] ?? null,
        ];

        return $this->apbdesRepository->create($payload);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->apbdesRepository->find($id);
        if ($record) {
            $payload = [
                'tahun' => $data['tahun'] ?? $record->tahun,
                'tipe' => strtolower($data['kategori'] ?? $record->tipe),
                'sub_kategori' => $data['sub_kategori'] ?? $record->sub_kategori,
                'kategori' => $data['nama_item'] ?? $record->kategori,
                'jumlah' => $data['anggaran'] ?? $data['jumlah'] ?? $record->jumlah,
                'realisasi' => $data['realisasi'] ?? $record->realisasi,
                'keterangan' => $data['keterangan'] ?? $record->keterangan,
            ];
            return $record->update($payload);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->apbdesRepository->delete($id);
    }

    public function getBudgetSummary(int $tahun = 0): array
    {
        if ($tahun === 0) {
            $tahun = (int)date('Y');
        }

        $items = Apbdes::where('tahun', $tahun)->get();

        $revenue = $items->where('tipe', 'pendapatan');
        $spending = $items->where('tipe', 'belanja');
        $financing = $items->where('tipe', 'pembiayaan');

        return [
            'tahun' => $tahun,
            
            'total_pendapatan_anggaran' => $revenue->sum('jumlah'),
            'total_pendapatan_realisasi' => $revenue->sum('realisasi'),

            'total_belanja_anggaran' => $spending->sum('jumlah'),
            'total_belanja_realisasi' => $spending->sum('realisasi'),

            'total_pembiayaan_anggaran' => $financing->sum('jumlah'),
            'total_pembiayaan_realisasi' => $financing->sum('realisasi'),
        ];
    }
}
