<?php

namespace App\Services;

use App\Interfaces\DusunRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Dusun;
use Illuminate\Database\Eloquent\Collection;

class DusunService
{
    protected DusunRepositoryInterface $dusunRepository;

    public function __construct(DusunRepositoryInterface $dusunRepository)
    {
        $this->dusunRepository = $dusunRepository;
    }

    public function getAll(): Collection
    {
        return $this->dusunRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = Dusun::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function storeDusun(array $data): Dusun
    {
        return $this->dusunRepository->create($data);
    }

    public function updateDusun(int $id, array $data): bool
    {
        return $this->dusunRepository->update($id, $data);
    }

    public function deleteDusun(int $id): bool
    {
        return $this->dusunRepository->delete($id);
    }

    public function findDusun(int $id): ?Dusun
    {
        return $this->dusunRepository->find($id);
    }
}
