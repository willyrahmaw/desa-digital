<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRtRequest;
use App\Services\RtService;
use App\Services\RwService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RtController extends Controller
{
    protected RtService $rtService;
    protected RwService $rwService;

    public function __construct(RtService $rtService, RwService $rwService)
    {
        $this->rtService = $rtService;
        $this->rwService = $rwService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $rwId = (int)$request->get('rw_id', 0);
        
        $rts = $this->rtService->getPaginatedList(10, $search, $rwId);
        $rws = $this->rwService->getAll();

        return view('admin.master.rt.index', compact('rts', 'rws', 'search', 'rwId'));
    }

    public function store(StoreRtRequest $request): RedirectResponse
    {
        $this->rtService->storeRt($request->validated());

        return redirect()->route('admin.master.rt.index')
            ->with('success', 'RT baru berhasil ditambahkan.');
    }

    public function update(StoreRtRequest $request, $id): RedirectResponse
    {
        $this->rtService->updateRt($id, $request->validated());

        return redirect()->route('admin.master.rt.index')
            ->with('success', 'Data RT berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->rtService->deleteRt($id);

        return redirect()->route('admin.master.rt.index')
            ->with('success', 'RT berhasil dihapus dari sistem.');
    }
}
