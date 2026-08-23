<?php

namespace App\Services;

use App\Interfaces\UmkmProdukRepositoryInterface;
use App\Models\UmkmProduk;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UmkmService
{
    protected UmkmProdukRepositoryInterface $umkmRepository;

    public function __construct(UmkmProdukRepositoryInterface $umkmRepository)
    {
        $this->umkmRepository = $umkmRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = UmkmProduk::with(['umkmPelaku', 'umkmKategori']);

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): UmkmProduk
    {
        if (isset($data['foto_file'])) {
            $path = $data['foto_file']->store('umkm', 'public');
            $data['foto'] = $path;
        }

        return $this->umkmRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->umkmRepository->find($id);
        if ($record) {
            if (isset($data['foto_file'])) {
                $path = $data['foto_file']->store('umkm', 'public');
                $data['foto'] = $path;
            }
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->umkmRepository->delete($id);
    }
}
