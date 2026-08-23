<?php

namespace App\Services;

use App\Models\RiwayatNomorSurat;
use Exception;

class DocumentValidationService
{
    protected array $allowedTokens = [
        'kode',
        'nomor',
        'bulan',
        'bulan_romawi',
        'tahun',
        'tahun_pendek',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'prefix',
        'suffix',
        'jenis'
    ];

    public function validateFormatPattern(string $pattern): bool
    {
        // Extract all tokens inside curly braces {}
        preg_match_all('/\{([a-zA-Z_]+)\}/', $pattern, $matches);
        
        if (empty($matches[1])) {
            throw new Exception("Format harus memiliki minimal satu token dinamis (seperti {nomor}).");
        }

        foreach ($matches[1] as $token) {
            if (!in_array(strtolower($token), $this->allowedTokens)) {
                throw new Exception("Token '{{$token}}' tidak dikenal oleh sistem.");
            }
        }

        // Check for illegal path/URL characters
        if (preg_match('/[<>:"|?*]/', $pattern)) {
            throw new Exception("Format nomor mengandung karakter ilegal.");
        }

        return true;
    }

    public function isNumberUnique(string $number, ?string $excludeUuid = null): bool
    {
        $query = RiwayatNomorSurat::where('nomor_surat', $number);

        if ($excludeUuid) {
            $query->where('uuid', '<>', $excludeUuid);
        }

        return !$query->exists();
    }
}
