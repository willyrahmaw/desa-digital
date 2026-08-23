<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApbdesRequest;
use App\Services\ApbdesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApbdesController extends Controller
{
    protected ApbdesService $apbdesService;

    public function __construct(ApbdesService $apbdesService)
    {
        $this->apbdesService = $apbdesService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');
        $tahun = (int)$request->get('tahun', date('Y'));

        $items = $this->apbdesService->getPaginatedList(5000, $search, $kategori, $tahun);
        $summary = $this->apbdesService->getBudgetSummary($tahun);

        return view('admin.master.apbdes.index', compact('items', 'summary', 'search', 'kategori', 'tahun'));
    }

    public function store(StoreApbdesRequest $request): RedirectResponse
    {
        $this->apbdesService->store($request->validated());

        return redirect()->route('admin.master.apbdes.index')
            ->with('success', 'Item APBDes berhasil ditambahkan.');
    }

    public function update(StoreApbdesRequest $request, $id): RedirectResponse
    {
        $this->apbdesService->update($id, $request->validated());

        return redirect()->route('admin.master.apbdes.index')
            ->with('success', 'Item APBDes berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->apbdesService->delete($id);

        return redirect()->route('admin.master.apbdes.index')
            ->with('success', 'Item APBDes berhasil dihapus.');
    }
}
