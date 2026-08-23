<?php

namespace App\Services;

use App\Models\DocumentSequence;
use App\Models\PengaturanPenomoran;
use Illuminate\Support\Facades\DB;

class DocumentSequenceService
{
    public function getNextSequence(PengaturanPenomoran $format, string $dateString): int
    {
        $sequenceKey = $this->resolveSequenceKey($format->reset_nomor, $dateString);

        return DB::transaction(function () use ($format, $sequenceKey) {
            // Pessimistic locking of the sequence row
            $sequence = DocumentSequence::where('format_id', $format->id)
                ->where('sequence_key', $sequenceKey)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = DocumentSequence::create([
                    'format_id' => $format->id,
                    'sequence_key' => $sequenceKey,
                    'current_value' => 0
                ]);
            }

            $newValue = $sequence->current_value + 1;
            $sequence->update(['current_value' => $newValue]);

            return $newValue;
        });
    }

    public function resolveSequenceKey(string $resetMode, string $dateString): string
    {
        $time = strtotime($dateString) ?: time();

        return match ($resetMode) {
            'yearly' => date('Y', $time),
            'monthly' => date('Y-m', $time),
            'daily' => date('Y-m-d', $time),
            default => 'global',
        };
    }
}
