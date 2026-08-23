<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDusunRequest;
use App\Services\DusunService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DusunController extends Controller
{
    protected DusunService $dusunService;

    public function __construct(DusunService $dusunService)
    {
        $this->dusunService = $dusunService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $dusuns = $this->dusunService->getPaginatedList(10, $search);

        return view('admin.master.dusun.index', compact('dusuns', 'search'));
    }

    public function store(StoreDusunRequest $request): RedirectResponse
    {
        $this->dusunService->storeDusun($request->validated());

        return redirect()->route('admin.master.dusun.index')
            ->with('success', 'Dusun baru berhasil ditambahkan.');
    }

    public function update(StoreDusunRequest $request, $id): RedirectResponse
    {
        $this->dusunService->updateDusun($id, $request->validated());

        return redirect()->route('admin.master.dusun.index')
            ->with('success', 'Data dusun berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->dusunService->deleteDusun($id);

        return redirect()->route('admin.master.dusun.index')
            ->with('success', 'Dusun berhasil dihapus dari sistem.');
    }
}
