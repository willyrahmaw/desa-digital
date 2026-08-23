<?php

namespace App\Services;

use App\Interfaces\PerangkatDesaRepositoryInterface;
use App\Models\PerangkatDesa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class PerangkatDesaService
{
    protected PerangkatDesaRepositoryInterface $perangkatRepository;

    public function __construct(PerangkatDesaRepositoryInterface $perangkatRepository)
    {
        $this->perangkatRepository = $perangkatRepository;
    }

    public function getAll(): Collection
    {
        return $this->perangkatRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = PerangkatDesa::with(['user', 'jabatan']);

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): PerangkatDesa
    {
        if (isset($data['foto_file'])) {
            $path = $data['foto_file']->store('perangkat_desa', 'public');
            $data['foto'] = $path;
        }

        return $this->perangkatRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->perangkatRepository->find($id);
        if (!$record) {
            return false;
        }

        if (isset($data['foto_file'])) {
            if ($record->foto) {
                Storage::disk('public')->delete($record->foto);
            }
            $path = $data['foto_file']->store('perangkat_desa', 'public');
            $data['foto'] = $path;
        }

        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = $this->perangkatRepository->find($id);
        if ($record) {
            if ($record->foto) {
                Storage::disk('public')->delete($record->foto);
            }
            return $record->delete();
        }
        return false;
    }
}
