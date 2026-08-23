<?php

namespace App\Services;

use App\Models\RiwayatNomorSurat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class DocumentHistoryService
{
    public function logNumber(array $data): RiwayatNomorSurat
    {
        $data['uuid'] = (string) Str::uuid();
        $data['status'] = $data['status'] ?? 'draft';
        $data['tanggal'] = $data['tanggal'] ?? now()->toDateString();

        return RiwayatNomorSurat::create($data);
    }

    public function updateStatus(string $number, string $status): bool
    {
        $record = RiwayatNomorSurat::where('nomor_surat', $number)->first();
        if ($record) {
            return $record->update(['status' => $status]);
        }
        return false;
    }

    public function getPaginatedList(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = RiwayatNomorSurat::with(['template', 'penduduk', 'petugas']);

        if (!empty($search)) {
            $query->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
        }

        return $query->latest()->paginate($perPage);
    }
}
