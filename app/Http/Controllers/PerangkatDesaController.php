<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerangkatDesaRequest;
use App\Services\PerangkatDesaService;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerangkatDesaController extends Controller
{
    protected PerangkatDesaService $perangkatService;

    public function __construct(PerangkatDesaService $perangkatService)
    {
        $this->perangkatService = $perangkatService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $perangkatList = $this->perangkatService->getPaginatedList(10, $search);
        
        $jabatans = Jabatan::all();
        $users = User::all();

        return view('admin.master.perangkat_desa.index', compact('perangkatList', 'jabatans', 'users', 'search'));
    }

    public function store(StorePerangkatDesaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->has('status_aktif');
        
        $this->perangkatService->store($data);

        return redirect()->route('admin.master.perangkat_desa.index')
            ->with('success', 'Perangkat Desa baru berhasil ditambahkan.');
    }

    public function update(StorePerangkatDesaRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->has('status_aktif');

        $this->perangkatService->update($id, $data);

        return redirect()->route('admin.master.perangkat_desa.index')
            ->with('success', 'Data Perangkat Desa berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->perangkatService->delete($id);

        return redirect()->route('admin.master.perangkat_desa.index')
            ->with('success', 'Perangkat Desa berhasil dihapus dari sistem.');
    }
}
