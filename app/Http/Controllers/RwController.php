<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRwRequest;
use App\Services\RwService;
use App\Services\DusunService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RwController extends Controller
{
    protected RwService $rwService;
    protected DusunService $dusunService;

    public function __construct(RwService $rwService, DusunService $dusunService)
    {
        $this->rwService = $rwService;
        $this->dusunService = $dusunService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $dusunId = (int)$request->get('dusun_id', 0);
        
        $rws = $this->rwService->getPaginatedList(10, $search, $dusunId);
        $dusuns = $this->dusunService->getAll();

        return view('admin.master.rw.index', compact('rws', 'dusuns', 'search', 'dusunId'));
    }

    public function store(StoreRwRequest $request): RedirectResponse
    {
        $this->rwService->storeRw($request->validated());

        return redirect()->route('admin.master.rw.index')
            ->with('success', 'RW baru berhasil ditambahkan.');
    }

    public function update(StoreRwRequest $request, $id): RedirectResponse
    {
        $this->rwService->updateRw($id, $request->validated());

        return redirect()->route('admin.master.rw.index')
            ->with('success', 'Data RW berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->rwService->deleteRw($id);

        return redirect()->route('admin.master.rw.index')
            ->with('success', 'RW berhasil dihapus dari sistem.');
    }
}
