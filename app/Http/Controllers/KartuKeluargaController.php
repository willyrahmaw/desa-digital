<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKartuKeluargaRequest;
use App\Services\KartuKeluargaService;
use App\Services\DusunService;
use App\Services\RwService;
use App\Services\RtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KartuKeluargaController extends Controller
{
    protected KartuKeluargaService $kkService;
    protected DusunService $dusunService;
    protected RwService $rwService;
    protected RtService $rtService;

    public function __construct(
        KartuKeluargaService $kkService,
        DusunService $dusunService,
        RwService $rwService,
        RtService $rtService
    ) {
        $this->kkService = $kkService;
        $this->dusunService = $dusunService;
        $this->rwService = $rwService;
        $this->rtService = $rtService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $dusunId = (int)$request->get('dusun_id', 0);

        $kkList = $this->kkService->getPaginatedList(5000, $search, $dusunId);
        $dusuns = $this->dusunService->getAll();
        $rws = $this->rwService->getAll();
        $rts = $this->rtService->getAll();

        return view('admin.master.kartu_keluarga.index', compact('kkList', 'dusuns', 'rws', 'rts', 'search', 'dusunId'));
    }

    public function store(StoreKartuKeluargaRequest $request): RedirectResponse
    {
        $this->kkService->store($request->validated());

        return redirect()->route('admin.master.kartu_keluarga.index')
            ->with('success', 'Kartu Keluarga baru berhasil ditambahkan.');
    }

    public function update(StoreKartuKeluargaRequest $request, $no_kk): RedirectResponse
    {
        $this->kkService->update($no_kk, $request->validated());

        return redirect()->route('admin.master.kartu_keluarga.index')
            ->with('success', 'Data Kartu Keluarga berhasil diperbarui.');
    }

    public function destroy($no_kk): RedirectResponse
    {
        $this->kkService->delete($no_kk);

        return redirect()->route('admin.master.kartu_keluarga.index')
            ->with('success', 'Kartu Keluarga berhasil dihapus dari sistem.');
    }
}
