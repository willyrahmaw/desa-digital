<?php

namespace App\Models;

use App\Traits\HasAesEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatNomorSurat extends Model
{
    use HasAesEncryption;

    protected $table = 'riwayat_nomor_surat';

    protected array $encrypted = [
        'penduduk_nik',
    ];

    protected $fillable = [
        'uuid',
        'nomor_surat',
        'jenis_surat',
        'template_id',
        'penduduk_nik',
        'tanggal',
        'petugas_id',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_nik', 'nik');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
