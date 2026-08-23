<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendudukRequest;
use App\Services\PendudukService;
use App\Services\KartuKeluargaService;
use App\Services\DusunService;
use App\Services\RwService;
use App\Services\RtService;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\GolonganDarah;
use App\Models\StatusKawin;
use App\Models\StatusTinggal;
use App\Models\Kewarganegaraan;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendudukController extends Controller
{
    protected PendudukService $pendudukService;
    protected KartuKeluargaService $kkService;
    protected DusunService $dusunService;
    protected RwService $rwService;
    protected RtService $rtService;

    public function __construct(
        PendudukService $pendudukService,
        KartuKeluargaService $kkService,
        DusunService $dusunService,
        RwService $rwService,
        RtService $rtService
    ) {
        $this->pendudukService = $pendudukService;
        $this->kkService = $kkService;
        $this->dusunService = $dusunService;
        $this->rwService = $rwService;
        $this->rtService = $rtService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $filters = [
            'dusun_id' => $request->get('dusun_id'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
        ];

        $penduduks = $this->pendudukService->getPaginatedList(5000, $search, $filters);
        $dusuns = $this->dusunService->getAll();

        return view('admin.master.penduduk.index', compact('penduduks', 'dusuns', 'search', 'filters'));
    }

    public function create(): View
    {
        $kkList = $this->kkService->getAll();
        $dusuns = $this->dusunService->getAll();
        $rws = $this->rwService->getAll();
        $rts = $this->rtService->getAll();
        
        $agamas = Agama::all();
        $pendidikans = Pendidikan::all();
        $pekerjaans = Pekerjaan::all();
        $goldars = GolonganDarah::all();
        $kawins = StatusKawin::all();
        $tinggals = StatusTinggal::all();
        $wargas = Kewarganegaraan::all();

        return view('admin.master.penduduk.create', compact(
            'kkList', 'dusuns', 'rws', 'rts', 'agamas', 'pendidikans', 'pekerjaans', 'goldars', 'kawins', 'tinggals', 'wargas'
        ));
    }

    public function store(StorePendudukRequest $request): RedirectResponse
    {
        $this->pendudukService->store($request->validated());

        return redirect()->route('admin.master.penduduk.index')
            ->with('success', 'Penduduk baru berhasil ditambahkan.');
    }

    public function edit($nik): View
    {
        $penduduk = Penduduk::findOrFail($nik);
        $kkList = $this->kkService->getAll();
        $dusuns = $this->dusunService->getAll();
        $rws = $this->rwService->getAll();
        $rts = $this->rtService->getAll();
        
        $agamas = Agama::all();
        $pendidikans = Pendidikan::all();
        $pekerjaans = Pekerjaan::all();
        $goldars = GolonganDarah::all();
        $kawins = StatusKawin::all();
        $tinggals = StatusTinggal::all();
        $wargas = Kewarganegaraan::all();

        return view('admin.master.penduduk.edit', compact(
            'penduduk', 'kkList', 'dusuns', 'rws', 'rts', 'agamas', 'pendidikans', 'pekerjaans', 'goldars', 'kawins', 'tinggals', 'wargas'
        ));
    }

    public function update(StorePendudukRequest $request, $nik): RedirectResponse
    {
        $this->pendudukService->update($nik, $request->validated());

        return redirect()->route('admin.master.penduduk.index')
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy($nik): RedirectResponse
    {
        $this->pendudukService->delete($nik);

        return redirect()->route('admin.master.penduduk.index')
            ->with('success', 'Data penduduk berhasil dihapus (soft delete).');
    }
}
