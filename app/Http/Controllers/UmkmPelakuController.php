<?php

namespace App\Http\Controllers;

use App\Interfaces\UmkmPelakuRepositoryInterface;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UmkmPelakuController extends Controller
{
    protected UmkmPelakuRepositoryInterface $pelakuRepository;

    public function __construct(UmkmPelakuRepositoryInterface $pelakuRepository)
    {
        $this->pelakuRepository = $pelakuRepository;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $query = \App\Models\UmkmPelaku::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
        }

        $pelakus = $query->latest()->paginate(10);
        $users = User::all();

        return view('admin.master.umkm.pelaku', compact('pelakus', 'users', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'user_id' => ['nullable', 'exists:user,id'],
        ]);

        $this->pelakuRepository->create($data);

        return redirect()->route('admin.master.umkm-pelaku.index')
            ->with('success', 'Pelaku UMKM berhasil didaftarkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'user_id' => ['nullable', 'exists:user,id'],
        ]);

        $this->pelakuRepository->update($id, $data);

        return redirect()->route('admin.master.umkm-pelaku.index')
            ->with('success', 'Data Pelaku UMKM berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->pelakuRepository->delete($id);

        return redirect()->route('admin.master.umkm-pelaku.index')
            ->with('success', 'Data Pelaku UMKM berhasil dihapus.');
    }
}
