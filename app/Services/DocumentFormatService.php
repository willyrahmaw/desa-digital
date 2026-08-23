<?php

namespace App\Services;

use App\Models\PengaturanPenomoran;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DocumentFormatService
{
    public function getAll(): Collection
    {
        return PengaturanPenomoran::all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = PengaturanPenomoran::query();

        if (!empty($search)) {
            $query->where('nama_format', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function getActiveFormatForType(string $type): ?PengaturanPenomoran
    {
        return PengaturanPenomoran::where('jenis_surat', $type)
            ->where('status', true)
            ->first();
    }

    public function store(array $data): PengaturanPenomoran
    {
        return PengaturanPenomoran::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = PengaturanPenomoran::findOrFail($id);
        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = PengaturanPenomoran::findOrFail($id);
        return $record->delete();
    }
}
