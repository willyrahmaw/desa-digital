<?php

namespace App\Services;

use App\Interfaces\PengaduanRepositoryInterface;
use App\Models\Pengaduan;
use Illuminate\Pagination\LengthAwarePaginator;

class PengaduanService
{
    protected PengaduanRepositoryInterface $pengaduanRepository;

    public function __construct(PengaduanRepositoryInterface $pengaduanRepository)
    {
        $this->pengaduanRepository = $pengaduanRepository;
    }

    public function getPaginatedList(int $perPage = 1000, string $search = '', string $status = ''): LengthAwarePaginator
    {
        $query = Pengaduan::with('pelapor');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_tiket', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('pelapor_nik', 'like', "%{$search}%");
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $query->latest('created_at');

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Pengaduan
    {
        return Pengaduan::with('pelapor')->find($id);
    }

    public function store(array $data): Pengaduan
    {
        $data['status'] = $data['status'] ?? 'pending';
        
        if (isset($data['foto_file'])) {
            $path = $data['foto_file']->store('pengaduan', 'public');
            $data['lampiran'] = $path;
        }

        return $this->pengaduanRepository->create($data);
    }

    public function respond(int $id, array $data): bool
    {
        $record = Pengaduan::find($id);
        if ($record) {
            return $record->update([
                'balasan' => $data['balasan'] ?? ($data['tanggapan'] ?? ''),
                'status'  => $data['status'] ?? 'resolved',
            ]);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->pengaduanRepository->delete($id);
    }
}
