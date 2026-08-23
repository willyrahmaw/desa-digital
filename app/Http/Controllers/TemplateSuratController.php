<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemplateSuratRequest;
use App\Services\TemplateSuratService;
use App\Models\TemplateSurat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateSuratController extends Controller
{
    protected TemplateSuratService $templateService;

    public function __construct(TemplateSuratService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $templates = $this->templateService->getPaginatedList(10, $search);

        return view('admin.master.template_surat.index', compact('templates', 'search'));
    }

    public function create(): View
    {
        return view('admin.master.template_surat.editor');
    }

    public function store(StoreTemplateSuratRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->has('status_aktif');
        $data['dengan_kop'] = $request->has('dengan_kop');
        $this->templateService->store($data);

        return redirect()->route('admin.master.template_surat.index')
            ->with('success', 'Template surat baru berhasil disimpan.');
    }

    public function edit($id): View
    {
        $template = TemplateSurat::findOrFail($id);
        return view('admin.master.template_surat.editor', compact('template'));
    }

    public function update(StoreTemplateSuratRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->has('status_aktif');
        $data['dengan_kop'] = $request->has('dengan_kop');
        $this->templateService->update($id, $data);

        return redirect()->route('admin.master.template_surat.index')
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->templateService->delete($id);

        return redirect()->route('admin.master.template_surat.index')
            ->with('success', 'Template surat berhasil dihapus.');
    }
}
