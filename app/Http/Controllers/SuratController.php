<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratRequest;
use App\Services\SuratService;
use App\Services\TemplateSuratService;
use App\Services\PendudukService;
use App\Services\DocumentNumberService;
use App\Services\DocumentFormatService;
use App\Models\Surat;
use App\Models\PerangkatDesa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratController extends Controller
{
    protected SuratService $suratService;
    protected TemplateSuratService $templateService;
    protected PendudukService $pendudukService;
    protected DocumentNumberService $numberService;
    protected DocumentFormatService $formatService;

    public function __construct(
        SuratService $suratService,
        TemplateSuratService $templateService,
        PendudukService $pendudukService,
        DocumentNumberService $numberService,
        DocumentFormatService $formatService
    ) {
        $this->suratService = $suratService;
        $this->templateService = $templateService;
        $this->pendudukService = $pendudukService;
        $this->numberService = $numberService;
        $this->formatService = $formatService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $filters = [
            'status_pengajuan' => $request->get('status_pengajuan'),
        ];

        $suratList = $this->suratService->getPaginatedList(5000, $search, $filters);
        $templates = $this->templateService->getAll();
        $penduduks = $this->pendudukService->getAll();
        $perangkatList = PerangkatDesa::where('status_aktif', true)->get();

        return view('admin.master.surat.index', compact('suratList', 'templates', 'penduduks', 'perangkatList', 'search', 'filters'));
    }

    public function create(Request $request): View
    {
        $templates = \App\Models\TemplateSurat::where('is_active', true)->get();
        $penduduks = \App\Models\Penduduk::with(['dataSosial', 'dusun', 'rt', 'rw'])
            ->orderBy('nama')
            ->get();

        $selectedNik = $request->get('nik');
        $selectedTemplateId = $request->get('template_id');

        return view('admin.master.surat.create', compact('templates', 'penduduks', 'selectedNik', 'selectedTemplateId'));
    }

    public function store(StoreSuratRequest $request): RedirectResponse
    {
        $this->suratService->store($request->validated());

        return redirect()->route('admin.master.surat.index')
            ->with('success', 'Permohonan surat baru berhasil diajukan.');
    }

    /**
     * AJAX: Preview the next number for a given template/jenis surat.
     */
    public function previewNomor(Request $request)
    {
        $templateId = $request->get('template_id');
        $jenisSurat = null;

        if ($templateId) {
            $template = \App\Models\TemplateSurat::find($templateId);
            $jenisSurat = $template?->jenis_surat ?? $template?->kode_surat;
        }

        if (!$jenisSurat) {
            return response()->json(['nomor' => null, 'message' => 'Format tidak dikonfigurasi']);
        }

        $format = $this->formatService->getActiveFormatForType($jenisSurat);

        if (!$format) {
            return response()->json(['nomor' => null, 'message' => 'Format penomoran belum dikonfigurasi untuk jenis ini']);
        }

        $preview = $this->numberService->previewNextNumber($format, now()->toDateString());

        return response()->json(['nomor' => $preview]);
    }

    public function approve(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'no_surat'              => ['required', 'string', 'max:100'],
            'ttd_oleh_perangkat_id' => ['required', 'exists:perangkat_desa,id'],
        ]);

        $surat = Surat::findOrFail($id);

        $this->suratService->update($id, [
            'no_surat'              => $request->no_surat ?: $surat->no_surat,
            'ttd_oleh_perangkat_id' => $request->ttd_oleh_perangkat_id,
            'status_pengajuan'      => 'Disetujui',
        ]);

        return redirect()->route('admin.master.surat.index')
            ->with('success', 'Permohonan surat berhasil disetujui.');
    }

    public function reject($id): RedirectResponse
    {
        $this->suratService->update($id, [
            'status_pengajuan' => 'Ditolak',
        ]);

        return redirect()->route('admin.master.surat.index')
            ->with('success', 'Permohonan surat ditolak.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->suratService->delete($id);

        return redirect()->route('admin.master.surat.index')
            ->with('success', 'Pengajuan surat berhasil dihapus dari sistem.');
    }

    public function print($id): View
    {
        $surat   = Surat::with(['templateSurat', 'penduduk', 'ttdOlehPerangkat.jabatan'])->findOrFail($id);
        $content = $this->suratService->generateLetterContent($surat);
        $settings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();

        return view('admin.master.surat.print', compact('surat', 'content', 'settings'));
    }
}
