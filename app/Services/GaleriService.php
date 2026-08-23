<?php

namespace App\Services;

use App\Interfaces\AlbumGaleriRepositoryInterface;
use App\Models\AlbumGaleri;
use App\Models\FotoGaleri;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GaleriService
{
    protected AlbumGaleriRepositoryInterface $albumRepository;

    public function __construct(AlbumGaleriRepositoryInterface $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = AlbumGaleri::withCount('fotoGaleri');

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function storeAlbum(array $data): AlbumGaleri
    {
        if (isset($data['cover_file'])) {
            $path = $data['cover_file']->store('galeri', 'public');
            $data['cover'] = $path;
        }

        return $this->albumRepository->create($data);
    }

    public function updateAlbum(int $id, array $data): bool
    {
        $record = $this->albumRepository->find($id);
        if ($record) {
            if (isset($data['cover_file'])) {
                $path = $data['cover_file']->store('galeri', 'public');
                $data['cover'] = $path;
            }
            return $record->update($data);
        }
        return false;
    }

    public function deleteAlbum(int $id): bool
    {
        return $this->albumRepository->delete($id);
    }

    public function addPhoto(int $albumId, array $data): FotoGaleri
    {
        if (isset($data['file'])) {
            $path = $data['file']->store('galeri/foto', 'public');
            $data['file_path'] = $path;
        }

        $data['album_id'] = $albumId;

        return FotoGaleri::create($data);
    }

    public function deletePhoto(int $photoId): bool
    {
        $photo = FotoGaleri::find($photoId);
        if ($photo) {
            $photo->delete();
            return true;
        }
        return false;
    }
}
