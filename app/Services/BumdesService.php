<?php

namespace App\Services;

use App\Interfaces\BumdesLaporanRepositoryInterface;
use App\Models\BumdesLaporan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BumdesService
{
    protected BumdesLaporanRepositoryInterface $bumdesRepository;

    public function __construct(BumdesLaporanRepositoryInterface $bumdesRepository)
    {
        $this->bumdesRepository = $bumdesRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = BumdesLaporan::with(['bumdesUnit']);

        if (!empty($search)) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): BumdesLaporan
    {
        if (isset($data['file_path_file'])) {
            $path = $data['file_path_file']->store('bumdes', 'public');
            $data['file_path'] = $path;
        }

        return $this->bumdesRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->bumdesRepository->find($id);
        if ($record) {
            if (isset($data['file_path_file'])) {
                $path = $data['file_path_file']->store('bumdes', 'public');
                $data['file_path'] = $path;
            }
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->bumdesRepository->delete($id);
    }
}
