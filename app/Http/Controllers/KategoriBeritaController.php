<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriBeritaRequest;
use App\Models\KategoriBerita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KategoriBeritaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        
        $categories = KategoriBerita::withCount('beritas')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('admin.master.kategori_berita.index', compact('categories', 'search'));
    }

    public function store(StoreKategoriBeritaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']);

        KategoriBerita::create($data);

        return redirect()->route('admin.master.kategori_berita.index')
            ->with('success', 'Kategori berita baru berhasil ditambahkan.');
    }

    public function update(StoreKategoriBeritaRequest $request, $id): RedirectResponse
    {
        $category = KategoriBerita::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']);

        $category->update($data);

        return redirect()->route('admin.master.kategori_berita.index')
            ->with('success', 'Kategori berita berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $category = KategoriBerita::findOrFail($id);

        if ($category->beritas()->count() > 0) {
            return redirect()->route('admin.master.kategori_berita.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel berita.');
        }

        $category->delete();

        return redirect()->route('admin.master.kategori_berita.index')
            ->with('success', 'Kategori berita berhasil dihapus.');
    }
}
