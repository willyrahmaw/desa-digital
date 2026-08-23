<?php

namespace App\Services;

use App\Interfaces\AgendaRepositoryInterface;
use App\Models\Agenda;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AgendaService
{
    protected AgendaRepositoryInterface $agendaRepository;

    public function __construct(AgendaRepositoryInterface $agendaRepository)
    {
        $this->agendaRepository = $agendaRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = Agenda::query();

        if (!empty($search)) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): Agenda
    {
        return $this->agendaRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->agendaRepository->find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->agendaRepository->delete($id);
    }
}
