<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataSosialRequest;
use App\Services\DataSosialService;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataSosialController extends Controller
{
    protected DataSosialService $dataSosialService;

    public function __construct(DataSosialService $dataSosialService)
    {
        $this->dataSosialService = $dataSosialService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $filters = [
            'layak_sktm' => $request->get('layak_sktm'),
            'desil' => $request->get('desil'),
        ];

        $dataSosialList = $this->dataSosialService->getPaginatedList(5000, $search, $filters);
        $penduduks = Penduduk::orderBy('nama')->get();

        return view('admin.master.data_social.index', compact('dataSosialList', 'penduduks', 'search', 'filters'));
    }

    public function store(StoreDataSosialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['dtks'] = $request->has('dtks');
        $data['pkh'] = $request->has('pkh');
        $data['bpnt'] = $request->has('bpnt');
        $data['pbi'] = $request->has('pbi');
        $data['kpr'] = $request->has('kpr');
        $data['layak_sktm'] = $request->has('layak_sktm');

        $this->dataSosialService->store($data);

        return redirect()->route('admin.master.data_social.index')
            ->with('success', 'Data sosial penduduk berhasil ditambahkan.');
    }

    public function update(StoreDataSosialRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();
        $data['dtks'] = $request->has('dtks');
        $data['pkh'] = $request->has('pkh');
        $data['bpnt'] = $request->has('bpnt');
        $data['pbi'] = $request->has('pbi');
        $data['kpr'] = $request->has('kpr');
        $data['layak_sktm'] = $request->has('layak_sktm');

        $this->dataSosialService->update($id, $data);

        return redirect()->route('admin.master.data_social.index')
            ->with('success', 'Data sosial penduduk berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->dataSosialService->delete($id);

        return redirect()->route('admin.master.data_social.index')
            ->with('success', 'Data sosial berhasil dihapus.');
    }
}
