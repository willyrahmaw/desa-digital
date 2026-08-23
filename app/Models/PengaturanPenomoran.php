<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengaturanPenomoran extends Model
{
    protected $table = 'pengaturan_penomoran';

    protected $fillable = [
        'nama_format',
        'jenis_surat',
        'format_nomor',
        'separator',
        'reset_nomor',
        'digit_nomor',
        'awalan',
        'akhiran',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'digit_nomor' => 'integer',
    ];

    public function sequences(): HasMany
    {
        return $this->hasMany(DocumentSequence::class, 'format_id');
    }
}
