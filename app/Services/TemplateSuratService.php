<?php

namespace App\Services;

use App\Interfaces\TemplateSuratRepositoryInterface;
use App\Models\TemplateSurat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TemplateSuratService
{
    protected TemplateSuratRepositoryInterface $templateRepository;

    public function __construct(TemplateSuratRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function getAll(): Collection
    {
        return $this->templateRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = TemplateSurat::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_surat', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): TemplateSurat
    {
        return $this->templateRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->templateRepository->find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->templateRepository->delete($id);
    }
}
