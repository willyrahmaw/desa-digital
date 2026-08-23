<?php

namespace App\Http\Controllers;

use App\Services\PengaduanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    protected PengaduanService $pengaduanService;

    public function __construct(PengaduanService $pengaduanService)
    {
        $this->pengaduanService = $pengaduanService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $pengaduans = $this->pengaduanService->getPaginatedList(1000, $search, $status);

        return view('admin.master.pengaduan.index', compact('pengaduans', 'search', 'status'));
    }

    public function show(int $id, Request $request): JsonResponse|View
    {
        $pengaduan = $this->pengaduanService->find($id);

        if (!$pengaduan) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Pengaduan tidak ditemukan.'], 404);
            }
            return redirect()->route('admin.master.pengaduan.index')->with('error', 'Pengaduan tidak ditemukan.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($pengaduan);
        }

        return view('admin.master.pengaduan.show', compact('pengaduan'));
    }

    public function respond(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'balasan' => ['nullable', 'string'],
            'tanggapan' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $balasan = $request->input('balasan') ?? $request->input('tanggapan') ?? '';
        $status = strtolower($request->input('status'));

        // Normalize status names
        if ($status === 'selesai') $status = 'resolved';
        if ($status === 'proses') $status = 'process';
        if ($status === 'ditolak') $status = 'rejected';

        $this->pengaduanService->respond($id, [
            'balasan' => $balasan,
            'status'  => $status,
        ]);

        return redirect()->route('admin.master.pengaduan.index')
            ->with('success', 'Tanggapan & status pengaduan warga berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $this->pengaduanService->delete($id);

        return redirect()->route('admin.master.pengaduan.index')
            ->with('success', 'Data pengaduan berhasil dihapus.');
    }
}
