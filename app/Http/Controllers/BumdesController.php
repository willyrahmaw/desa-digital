<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBumdesRequest;
use App\Services\BumdesService;
use App\Models\BumdesUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BumdesController extends Controller
{
    protected BumdesService $bumdesService;

    public function __construct(BumdesService $bumdesService)
    {
        $this->bumdesService = $bumdesService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $laporans = $this->bumdesService->getPaginatedList(10, $search);
        $units = BumdesUnit::all();

        return view('admin.master.bumdes.index', compact('laporans', 'units', 'search'));
    }

    public function store(StoreBumdesRequest $request): RedirectResponse
    {
        $this->bumdesService->store($request->validated());

        return redirect()->route('admin.master.bumdes.index')
            ->with('success', 'Laporan BUMDes berhasil disimpan.');
    }

    public function update(StoreBumdesRequest $request, $id): RedirectResponse
    {
        $this->bumdesService->update($id, $request->validated());

        return redirect()->route('admin.master.bumdes.index')
            ->with('success', 'Laporan BUMDes berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->bumdesService->delete($id);

        return redirect()->route('admin.master.bumdes.index')
            ->with('success', 'Laporan BUMDes berhasil dihapus.');
    }
}
