<?php

namespace App\Http\Controllers;

use App\Interfaces\UmkmKategoriRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UmkmKategoriController extends Controller
{
    protected UmkmKategoriRepositoryInterface $kategoriRepository;

    public function __construct(UmkmKategoriRepositoryInterface $kategoriRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $query = \App\Models\UmkmKategori::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.master.umkm.kategori', compact('categories', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:umkm_kategori,nama'],
        ]);

        $data['slug'] = Str::slug($data['nama']);

        $this->kategoriRepository->create($data);

        return redirect()->route('admin.master.umkm-kategori.index')
            ->with('success', 'Kategori UMKM baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:umkm_kategori,nama,' . $id],
        ]);

        $data['slug'] = Str::slug($data['nama']);

        $this->kategoriRepository->update($id, $data);

        return redirect()->route('admin.master.umkm-kategori.index')
            ->with('success', 'Kategori UMKM berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->kategoriRepository->delete($id);

        return redirect()->route('admin.master.umkm-kategori.index')
            ->with('success', 'Kategori UMKM berhasil dihapus.');
    }
}
