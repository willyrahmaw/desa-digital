<?php

namespace App\Services;

use App\Models\PengaturanPenomoran;
use App\Models\KlasifikasiSurat;
use App\Models\Pengaturan;
use Exception;

class DocumentNumberService
{
    protected DocumentSequenceService $sequenceService;
    protected DocumentValidationService $validationService;
    protected DocumentHistoryService $historyService;

    public function __construct(
        DocumentSequenceService $sequenceService,
        DocumentValidationService $validationService,
        DocumentHistoryService $historyService
    ) {
        $this->sequenceService = $sequenceService;
        $this->validationService = $validationService;
        $this->historyService = $historyService;
    }

    public function generateNumber(string $jenisSurat, string $dateString, array $context = []): string
    {
        $format = PengaturanPenomoran::where('jenis_surat', $jenisSurat)
            ->where('status', true)
            ->first();

        if (!$format) {
            // Fallback default format if none is configured
            $format = new PengaturanPenomoran([
                'nama_format' => 'Default Format',
                'jenis_surat' => $jenisSurat,
                'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
                'separator' => '/',
                'reset_nomor' => 'yearly',
                'digit_nomor' => 3,
                'status' => true
            ]);
        }

        // 1. Generate sequence inside locked transaction
        $sequence = $this->sequenceService->getNextSequence($format, $dateString);

        // 2. Resolve final formatted number string
        $numberStr = $this->renderPattern($format, $sequence, $dateString, $context);

        // 3. Validate uniqueness
        if (!$this->validationService->isNumberUnique($numberStr)) {
            // If conflict, try once more with forced offset increment or throw exception
            throw new Exception("Nomor surat hasil generator '{$numberStr}' sudah terdaftar di database.");
        }

        // 4. Log to history
        $this->historyService->logNumber([
            'nomor_surat' => $numberStr,
            'jenis_surat' => $jenisSurat,
            'template_id' => $context['template_id'] ?? null,
            'penduduk_nik' => $context['penduduk_nik'] ?? null,
            'tanggal' => $dateString,
            'petugas_id' => auth()->id() ?? $context['petugas_id'] ?? null,
            'status' => $context['status'] ?? 'digunakan'
        ]);

        return $numberStr;
    }

    public function previewNextNumber(PengaturanPenomoran $format, string $dateString): string
    {
        $time = strtotime($dateString) ?: time();
        $sequenceKey = $this->sequenceService->resolveSequenceKey($format->reset_nomor, date('Y-m-d', $time));

        // Preview reads the value without incrementing it
        $sequenceObj = \App\Models\DocumentSequence::where('format_id', $format->id)
            ->where('sequence_key', $sequenceKey)
            ->first();

        $previewSequence = ($sequenceObj->current_value ?? 0) + 1;

        return $this->renderPattern($format, $previewSequence, date('Y-m-d', $time));
    }

    public function renderPattern(PengaturanPenomoran $format, int $sequence, string $dateString, array $context = []): string
    {
        $time = strtotime($dateString) ?: time();
        
        // Load settings values
        $settings = Pengaturan::pluck('value', 'key')->toArray();
        
        // Resolve classification code
        $classification = KlasifikasiSurat::where('nama', $format->jenis_surat)
            ->orWhere('kategori', $format->jenis_surat)
            ->first();
            
        $kode = $classification->kode ?? $context['kode_surat'] ?? '470';
        
        // Resolve tokens replacements
        $paddedNumber = str_pad($sequence, $format->digit_nomor, '0', STR_PAD_LEFT);
        
        $replacements = [
            '{kode}' => $kode,
            '{nomor}' => $paddedNumber,
            '{bulan}' => date('m', $time),
            '{bulan_romawi}' => $this->getRomanNumeral((int)date('m', $time)),
            '{tahun}' => date('Y', $time),
            '{tahun_pendek}' => date('y', $time),
            '{desa}' => $settings['nama_desa'] ?? 'Desa Candraloka',
            '{kecamatan}' => $settings['kecamatan'] ?? 'Astraguna',
            '{kabupaten}' => $settings['kabupaten'] ?? 'Nirwana Raya',
            '{provinsi}' => $settings['provinsi'] ?? 'Fantasia Nusantara',
            '{kode_pos}' => $settings['kode_pos'] ?? '99881',
            '{prefix}' => $format->awalan ?? '',
            '{suffix}' => $format->akhiran ?? '',
            '{jenis}' => $format->jenis_surat,
        ];

        $rendered = str_ireplace(array_keys($replacements), array_values($replacements), $format->format_nomor);
        
        // Clean double separators
        if (!empty($format->separator)) {
            $dblSep = $format->separator . $format->separator;
            $rendered = str_replace($dblSep, $format->separator, $rendered);
        }

        return trim($rendered);
    }

    protected function getRomanNumeral(int $month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$month] ?? '';
    }
}
