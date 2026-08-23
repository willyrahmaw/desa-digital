<?php

namespace App\Services;

use App\Interfaces\RtRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Rt;
use Illuminate\Database\Eloquent\Collection;

class RtService
{
    protected RtRepositoryInterface $rtRepository;

    public function __construct(RtRepositoryInterface $rtRepository)
    {
        $this->rtRepository = $rtRepository;
    }

    public function getAll(): Collection
    {
        return $this->rtRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', int $rwId = 0): LengthAwarePaginator
    {
        $query = Rt::with('rw.dusun');

        if (!empty($search)) {
            $query->where('nomor', 'like', "%{$search}%");
        }

        if ($rwId > 0) {
            $query->where('rw_id', $rwId);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function storeRt(array $data): Rt
    {
        return $this->rtRepository->create($data);
    }

    public function updateRt(int $id, array $data): bool
    {
        return $this->rtRepository->update($id, $data);
    }

    public function deleteRt(int $id): bool
    {
        return $this->rtRepository->delete($id);
    }

    public function findRt(int $id): ?Rt
    {
        return $this->rtRepository->find($id);
    }
}
