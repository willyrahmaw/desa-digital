<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUmkmRequest;
use App\Services\UmkmService;
use App\Models\UmkmPelaku;
use App\Models\UmkmKategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UmkmController extends Controller
{
    protected UmkmService $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $products = $this->umkmService->getPaginatedList(10, $search);
        
        $owners = UmkmPelaku::all();
        $categories = UmkmKategori::all();

        return view('admin.master.umkm.index', compact('products', 'owners', 'categories', 'search'));
    }

    public function store(StoreUmkmRequest $request): RedirectResponse
    {
        $this->umkmService->store($request->validated());

        return redirect()->route('admin.master.umkm.index')
            ->with('success', 'Produk UMKM berhasil ditambahkan ke katalog.');
    }

    public function update(StoreUmkmRequest $request, $id): RedirectResponse
    {
        $this->umkmService->update($id, $request->validated());

        return redirect()->route('admin.master.umkm.index')
            ->with('success', 'Produk UMKM berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->umkmService->delete($id);

        return redirect()->route('admin.master.umkm.index')
            ->with('success', 'Produk UMKM berhasil dihapus.');
    }
}
