<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaRequest;
use App\Services\AgendaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    protected AgendaService $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $agendas = $this->agendaService->getPaginatedList(10, $search);

        return view('admin.master.agenda.index', compact('agendas', 'search'));
    }

    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        $this->agendaService->store($request->validated());

        return redirect()->route('admin.master.agenda.index')
            ->with('success', 'Agenda baru berhasil ditambahkan.');
    }

    public function update(StoreAgendaRequest $request, $id): RedirectResponse
    {
        $this->agendaService->update($id, $request->validated());

        return redirect()->route('admin.master.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->agendaService->delete($id);

        return redirect()->route('admin.master.agenda.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
