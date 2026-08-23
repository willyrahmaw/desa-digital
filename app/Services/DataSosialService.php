<?php

namespace App\Services;

use App\Interfaces\DataSosialRepositoryInterface;
use App\Models\DataSosial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DataSosialService
{
    protected DataSosialRepositoryInterface $dataSosialRepository;

    public function __construct(DataSosialRepositoryInterface $dataSosialRepository)
    {
        $this->dataSosialRepository = $dataSosialRepository;
    }

    public function getAll(): Collection
    {
        return $this->dataSosialRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', array $filters = []): LengthAwarePaginator
    {
        $query = DataSosial::with(['penduduk.dusun', 'verifikator']);

        if (!empty($search)) {
            $query->whereHas('penduduk', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if (isset($filters['layak_sktm']) && $filters['layak_sktm'] !== '') {
            $query->where('layak_sktm', (bool)$filters['layak_sktm']);
        }

        if (isset($filters['desil']) && $filters['desil'] !== '') {
            $query->where('desil', (int)$filters['desil']);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): DataSosial
    {
        $data['verifikator_id'] = auth()->id();
        $data['tanggal_verifikasi'] = now()->toDateString();
        
        return DataSosial::updateOrCreate(
            ['penduduk_nik' => $data['penduduk_nik']],
            $data
        );
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->dataSosialRepository->find($id);
        if ($record) {
            $data['verifikator_id'] = auth()->id();
            $data['tanggal_verifikasi'] = now()->toDateString();
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->dataSosialRepository->delete($id);
    }
}
