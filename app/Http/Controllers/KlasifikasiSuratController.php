<?php

namespace App\Http\Controllers;

use App\Services\DocumentClassificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KlasifikasiSuratController extends Controller
{
    protected DocumentClassificationService $classificationService;

    public function __construct(DocumentClassificationService $classificationService)
    {
        $this->classificationService = $classificationService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $classifications = $this->classificationService->getPaginatedList(10, $search);

        return view('admin.master.klasifikasi_surat.index', compact('classifications', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:50', 'unique:klasifikasi_surat,kode'],
            'kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif,arsip'],
            'urutan' => ['required', 'integer', 'min:0'],
        ]);

        $this->classificationService->store($data);

        return redirect()->route('admin.master.klasifikasi_surat.index')
            ->with('success', 'Klasifikasi surat baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:50', 'unique:klasifikasi_surat,kode,' . $id],
            'kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif,arsip'],
            'urutan' => ['required', 'integer', 'min:0'],
        ]);

        $this->classificationService->update($id, $data);

        return redirect()->route('admin.master.klasifikasi_surat.index')
            ->with('success', 'Klasifikasi surat berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->classificationService->delete($id);

        return redirect()->route('admin.master.klasifikasi_surat.index')
            ->with('success', 'Klasifikasi surat berhasil dihapus dari sistem.');
    }
}
