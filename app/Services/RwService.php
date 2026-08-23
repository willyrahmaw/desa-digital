<?php

namespace App\Services;

use App\Interfaces\RwRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Rw;
use Illuminate\Database\Eloquent\Collection;

class RwService
{
    protected RwRepositoryInterface $rwRepository;

    public function __construct(RwRepositoryInterface $rwRepository)
    {
        $this->rwRepository = $rwRepository;
    }

    public function getAll(): Collection
    {
        return $this->rwRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', int $dusunId = 0): LengthAwarePaginator
    {
        $query = Rw::with('dusun');

        if (!empty($search)) {
            $query->where('nomor', 'like', "%{$search}%");
        }

        if ($dusunId > 0) {
            $query->where('dusun_id', $dusunId);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function storeRw(array $data): Rw
    {
        return $this->rwRepository->create($data);
    }

    public function updateRw(int $id, array $data): bool
    {
        return $this->rwRepository->update($id, $data);
    }

    public function deleteRw(int $id): bool
    {
        return $this->rwRepository->delete($id);
    }

    public function findRw(int $id): ?Rw
    {
        return $this->rwRepository->find($id);
    }
}
