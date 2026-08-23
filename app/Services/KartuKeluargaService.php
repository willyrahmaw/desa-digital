<?php

namespace App\Services;

use App\Interfaces\KartuKeluargaRepositoryInterface;
use App\Models\KartuKeluarga;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class KartuKeluargaService
{
    protected KartuKeluargaRepositoryInterface $kkRepository;

    public function __construct(KartuKeluargaRepositoryInterface $kkRepository)
    {
        $this->kkRepository = $kkRepository;
    }

    public function getAll(): Collection
    {
        return $this->kkRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', int $dusunId = 0): LengthAwarePaginator
    {
        $query = KartuKeluarga::with(['dusun', 'rw', 'rt', 'kepalaKeluarga']);

        if (!empty($search)) {
            $query->where('no_kk', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        if ($dusunId > 0) {
            $query->where('dusun_id', $dusunId);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): KartuKeluarga
    {
        return $this->kkRepository->create($data);
    }

    public function update(string $no_kk, array $data): bool
    {
        $record = $this->kkRepository->find($no_kk);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete(string $no_kk): bool
    {
        return $this->kkRepository->delete($no_kk);
    }
}
