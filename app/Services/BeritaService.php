<?php

namespace App\Services;

use App\Interfaces\BeritaRepositoryInterface;
use App\Models\Berita;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BeritaService
{
    protected BeritaRepositoryInterface $beritaRepository;

    public function __construct(BeritaRepositoryInterface $beritaRepository)
    {
        $this->beritaRepository = $beritaRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = Berita::with(['kategoriBerita', 'user']);

        if (!empty($search)) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): Berita
    {
        $data['slug'] = Str::slug($data['judul']);
        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'Draft';
        $data['tanggal_publikasi'] = $data['tanggal_publikasi'] ?? now()->toDateString();

        if (isset($data['gambar_file'])) {
            $path = $data['gambar_file']->store('berita', 'public');
            $data['gambar'] = $path;
        }

        return $this->beritaRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->beritaRepository->find($id);
        if ($record) {
            $data['slug'] = Str::slug($data['judul']);
            
            if (isset($data['gambar_file'])) {
                $path = $data['gambar_file']->store('berita', 'public');
                $data['gambar'] = $path;
            }

            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->beritaRepository->delete($id);
    }
}
