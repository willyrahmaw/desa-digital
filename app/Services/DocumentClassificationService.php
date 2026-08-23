<?php

namespace App\Services;

use App\Models\KlasifikasiSurat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DocumentClassificationService
{
    public function getAll(): Collection
    {
        return KlasifikasiSurat::orderBy('urutan')->get();
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = KlasifikasiSurat::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }

        return $query->orderBy('urutan')->paginate($perPage);
    }

    public function store(array $data): KlasifikasiSurat
    {
        return KlasifikasiSurat::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = KlasifikasiSurat::findOrFail($id);
        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = KlasifikasiSurat::findOrFail($id);
        return $record->delete();
    }
}
