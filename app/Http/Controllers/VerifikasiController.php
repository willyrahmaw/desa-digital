<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\View\View;

class VerifikasiController extends Controller
{
    public function verify(string $uuid): View
    {
        $surat = Surat::with(['templateSurat', 'penduduk', 'ttdOlehPerangkat.jabatan'])
            ->where('uuid', $uuid)
            ->first();

        $isValid = ($surat && $surat->status_pengajuan === 'Disetujui');

        return view('public.verifikasi', compact('surat', 'isValid'));
    }
}
