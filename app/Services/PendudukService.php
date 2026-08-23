<?php

namespace App\Services;

use App\Interfaces\PendudukRepositoryInterface;
use App\Models\Penduduk;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class PendudukService
{
    protected PendudukRepositoryInterface $pendudukRepository;

    public function __construct(PendudukRepositoryInterface $pendudukRepository)
    {
        $this->pendudukRepository = $pendudukRepository;
    }

    public function getAll(): Collection
    {
        return $this->pendudukRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', array $filters = []): LengthAwarePaginator
    {
        $query = Penduduk::with(['kartuKeluarga', 'agama', 'pendidikan', 'pekerjaan', 'dusun', 'rw', 'rt']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['dusun_id'])) {
            $query->where('dusun_id', $filters['dusun_id']);
        }
        
        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): Penduduk
    {
        if (isset($data['foto_file'])) {
            $path = $data['foto_file']->store('penduduk', 'public');
            $data['foto'] = $path;
        }

        $data['qr_code'] = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($data['nik']);

        return $this->pendudukRepository->create($data);
    }

    public function update(string $nik, array $data): bool
    {
        $record = $this->pendudukRepository->find($nik);
        if (!$record) {
            return false;
        }

        if (isset($data['foto_file'])) {
            if ($record->foto) {
                Storage::disk('public')->delete($record->foto);
            }
            $path = $data['foto_file']->store('penduduk', 'public');
            $data['foto'] = $path;
        }

        if (isset($data['nik']) && $data['nik'] !== $record->nik) {
            $data['qr_code'] = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($data['nik']);
        }

        return $record->update($data);
    }

    public function delete(string $nik): bool
    {
        $record = $this->pendudukRepository->find($nik);
        if ($record) {
            if ($record->foto) {
                Storage::disk('public')->delete($record->foto);
            }
            return $record->delete();
        }
        return false;
    }
}
