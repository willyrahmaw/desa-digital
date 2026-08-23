<?php

namespace App\Http\Controllers;

use App\Interfaces\BumdesUnitRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BumdesUnitController extends Controller
{
    protected BumdesUnitRepositoryInterface $unitRepository;

    public function __construct(BumdesUnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $query = \App\Models\BumdesUnit::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%");
        }

        $units = $query->latest()->paginate(10);

        return view('admin.master.bumdes.unit', compact('units', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'ketua' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $this->unitRepository->create($data);

        return redirect()->route('admin.master.bumdes-unit.index')
            ->with('success', 'Unit usaha BUMDes baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'ketua' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $this->unitRepository->update($id, $data);

        return redirect()->route('admin.master.bumdes-unit.index')
            ->with('success', 'Unit usaha BUMDes berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->unitRepository->delete($id);

        return redirect()->route('admin.master.bumdes-unit.index')
            ->with('success', 'Unit usaha BUMDes berhasil dihapus.');
    }
}
