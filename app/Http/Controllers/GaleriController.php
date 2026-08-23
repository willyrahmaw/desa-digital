<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlbumRequest;
use App\Services\GaleriService;
use App\Models\AlbumGaleri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GaleriController extends Controller
{
    protected GaleriService $galeriService;

    public function __construct(GaleriService $galeriService)
    {
        $this->galeriService = $galeriService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $albums = $this->galeriService->getPaginatedList(10, $search);

        return view('admin.master.galeri.index', compact('albums', 'search'));
    }

    public function store(StoreAlbumRequest $request): RedirectResponse
    {
        $this->galeriService->storeAlbum($request->validated());

        return redirect()->route('admin.master.galeri.index')
            ->with('success', 'Album galeri baru berhasil ditambahkan.');
    }

    public function update(StoreAlbumRequest $request, $id): RedirectResponse
    {
        $this->galeriService->updateAlbum($id, $request->validated());

        return redirect()->route('admin.master.galeri.index')
            ->with('success', 'Album galeri berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->galeriService->deleteAlbum($id);

        return redirect()->route('admin.master.galeri.index')
            ->with('success', 'Album galeri berhasil dihapus.');
    }
}
