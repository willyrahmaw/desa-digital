<?php

namespace App\Http\Controllers;

use App\Services\PublicService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PublicPengaduanController extends Controller
{
    protected PublicService $publicService;

    public function __construct(PublicService $publicService)
    {
        $this->publicService = $publicService;
    }

    public function index(): View
    {
        return view('public.pengaduan.index');
    }

    public function checkNik(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16']
        ]);

        $result = $this->publicService->validateNik($request->nik);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16'],
            'telepon' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'foto.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            $files = array_slice($request->file('foto'), 0, 5); // Limit max 5 photos
            foreach ($files as $file) {
                $path = $file->store('pengaduan_foto', 'public');
                $fotoPaths[] = $path;
            }
        }

        $result = $this->publicService->submitPengaduan($request->all(), $fotoPaths);

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('public.pengaduan.index')->with('success_ticket', [
            'ticket' => $result['nomor_tiket'],
            'message' => $result['message'],
        ]);
    }

    public function trackStatus(Request $request)
    {
        $request->validate([
            'nomor_tiket' => ['required', 'string', 'max:50'],
            'nik' => ['required', 'string', 'size:16'],
        ]);

        $result = $this->publicService->trackPengaduan($request->nomor_tiket, $request->nik);

        if (!$result['success']) {
            return redirect()->route('public.pengaduan.index')->with('error_track', $result['message']);
        }

        return redirect()->route('public.pengaduan.index')->with('tracked_data', $result['data']);
    }
}
