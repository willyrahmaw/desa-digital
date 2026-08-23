<?php

namespace App\Http\Controllers;

use App\Models\PengaturanPenomoran;
use App\Services\DocumentFormatService;
use App\Services\DocumentNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanPenomoranController extends Controller
{
    protected DocumentFormatService $formatService;
    protected DocumentNumberService $numberService;

    public function __construct(
        DocumentFormatService $formatService,
        DocumentNumberService $numberService
    ) {
        $this->formatService = $formatService;
        $this->numberService = $numberService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $formats = $this->formatService->getPaginatedList(10, $search);

        return view('admin.settings.penomoran.index', compact('formats', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_format'  => ['required', 'string', 'max:100'],
            'jenis_surat'  => ['required', 'string', 'max:100', 'unique:pengaturan_penomoran,jenis_surat'],
            'format_nomor' => ['required', 'string', 'max:255'],
            'separator'    => ['nullable', 'string', 'max:5'],
            'reset_nomor'  => ['required', 'in:none,yearly,monthly,daily,manual'],
            'digit_nomor'  => ['required', 'integer', 'min:1', 'max:10'],
            'awalan'       => ['nullable', 'string', 'max:50'],
            'akhiran'      => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable'],
        ]);

        $data['status'] = $request->boolean('status', true);

        $this->formatService->store($data);

        return redirect()->route('admin.settings.penomoran.index')
            ->with('success', 'Format penomoran baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'nama_format'  => ['required', 'string', 'max:100'],
            'jenis_surat'  => ['required', 'string', 'max:100', 'unique:pengaturan_penomoran,jenis_surat,' . $id],
            'format_nomor' => ['required', 'string', 'max:255'],
            'separator'    => ['nullable', 'string', 'max:5'],
            'reset_nomor'  => ['required', 'in:none,yearly,monthly,daily,manual'],
            'digit_nomor'  => ['required', 'integer', 'min:1', 'max:10'],
            'awalan'       => ['nullable', 'string', 'max:50'],
            'akhiran'      => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable'],
        ]);

        $data['status'] = $request->boolean('status', true);

        $this->formatService->update($id, $data);

        return redirect()->route('admin.settings.penomoran.index')
            ->with('success', 'Format penomoran berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->formatService->delete($id);

        return redirect()->route('admin.settings.penomoran.index')
            ->with('success', 'Format penomoran berhasil dihapus dari sistem.');
    }

    /**
     * AJAX preview: Generate a preview of the next number without committing to DB.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'format_nomor' => ['required', 'string'],
            'jenis_surat'  => ['nullable', 'string'],
            'reset_nomor'  => ['nullable', 'string'],
            'digit_nomor'  => ['nullable', 'integer'],
            'separator'    => ['nullable', 'string'],
            'awalan'       => ['nullable', 'string'],
            'akhiran'      => ['nullable', 'string'],
        ]);

        $format = new PengaturanPenomoran([
            'nama_format'  => 'Preview',
            'jenis_surat'  => $request->get('jenis_surat', 'SKTM'),
            'format_nomor' => $request->format_nomor,
            'separator'    => $request->get('separator', '/'),
            'reset_nomor'  => $request->get('reset_nomor', 'yearly'),
            'digit_nomor'  => (int) $request->get('digit_nomor', 3),
            'awalan'       => $request->get('awalan', ''),
            'akhiran'      => $request->get('akhiran', ''),
            'status'       => true,
        ]);

        $preview = $this->numberService->previewNextNumber($format, now()->toDateString());

        return response()->json(['preview' => $preview]);
    }
}
