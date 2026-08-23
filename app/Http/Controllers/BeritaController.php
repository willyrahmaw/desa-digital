<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeritaRequest;
use App\Services\BeritaService;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    protected BeritaService $beritaService;

    public function __construct(BeritaService $beritaService)
    {
        $this->beritaService = $beritaService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $beritas = $this->beritaService->getPaginatedList(5000, $search);
        $categories = KategoriBerita::all();

        return view('admin.master.berita.index', compact('beritas', 'categories', 'search'));
    }

    public function create(): View
    {
        $categories = KategoriBerita::all();
        return view('admin.master.berita.create', compact('categories'));
    }

    public function store(StoreBeritaRequest $request): RedirectResponse
    {
        $this->beritaService->store($request->validated());

        return redirect()->route('admin.master.berita.index')
            ->with('success', 'Berita / artikel baru berhasil diterbitkan.');
    }

    public function edit($id): View
    {
        $berita = Berita::findOrFail($id);
        $categories = KategoriBerita::all();

        return view('admin.master.berita.edit', compact('berita', 'categories'));
    }

    public function update(StoreBeritaRequest $request, $id): RedirectResponse
    {
        $this->beritaService->update($id, $request->validated());

        return redirect()->route('admin.master.berita.index')
            ->with('success', 'Berita / artikel berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->beritaService->delete($id);

        return redirect()->route('admin.master.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'files.*' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $file = $request->file('upload') ?? $request->file('files');
        if (is_array($file)) {
            $file = $file[0] ?? null;
        }

        if ($file) {
            $path = $file->store('berita/editor', 'public');
            $url = asset('storage/' . $path);

            return response()->json([
                'uploaded' => true,
                'url' => $url,
                'default' => $url,
                'files' => [$url],
                'isSuccess' => true,
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'isSuccess' => false,
            'error' => [
                'message' => 'Gagal mengunggah foto. Pastikan format file adalah JPG, PNG, atau WEBP (Maksimal 5MB).'
            ]
        ], 400);
    }
}
