<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSequence extends Model
{
    protected $table = 'document_sequences';

    protected $fillable = [
        'format_id',
        'sequence_key',
        'current_value',
    ];

    protected $casts = [
        'current_value' => 'integer',
    ];

    public function format(): BelongsTo
    {
        return $this->belongsTo(PengaturanPenomoran::class, 'format_id');
    }
}
