<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use App\Traits\HasAesEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    use SoftDeletes, HasAuditLog, HasAesEncryption;

    protected $table = 'surat';

    protected array $encrypted = [
        'penduduk_nik',
    ];

    protected $fillable = [
        'uuid',
        'nomor_surat',
        'no_surat',
        'jenis_surat',
        'penduduk_nik',
        'template_id',
        'template_surat_id',
        'file_path',
        'qr_code_path',
        'status',
        'status_pengajuan',
        'tanggal_terbit',
        'tanggal_pengajuan',
        'petugas_id',
        'signed_at',
        'signed_by_perangkat_id',
        'ttd_oleh_perangkat_id',
        'keperluan',
        'meta_data',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'signed_at' => 'datetime',
        'meta_data' => 'array',
    ];

    public function getKeperluanAttribute(): ?string
    {
        return $this->attributes['keperluan'] ?? ($this->meta_data['keperluan'] ?? ($this->meta_data['keterangan'] ?? null));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->template_id && isset($model->attributes['template_surat_id'])) {
                $model->template_id = $model->attributes['template_surat_id'];
            }

            if (!$model->jenis_surat && $model->template_id) {
                $template = \App\Models\TemplateSurat::find($model->template_id);
                $model->jenis_surat = $template?->jenis_surat ?? $template?->nama ?? 'Surat Keterangan';
            }

            if (!$model->jenis_surat) {
                $model->jenis_surat = 'Surat Keterangan';
            }

            if (!$model->tanggal_terbit) {
                $model->tanggal_terbit = now()->toDateString();
            }

            // Auto-generate nomor_surat if empty
            if (empty($model->attributes['nomor_surat'])) {
                try {
                    $template = $model->template_id ? \App\Models\TemplateSurat::find($model->template_id) : null;
                    $jenisSurat = $template?->jenis_surat ?? $template?->kode_surat ?? $template?->nama ?? $model->jenis_surat;
                    
                    if ($jenisSurat) {
                        /** @var \App\Services\DocumentNumberService $numberService */
                        $numberService = app(\App\Services\DocumentNumberService::class);
                        $model->attributes['nomor_surat'] = $numberService->generateNumber(
                            $jenisSurat,
                            $model->tanggal_terbit ?? now()->toDateString(),
                            [
                                'template_id'  => $model->template_id,
                                'penduduk_nik' => $model->penduduk_nik,
                                'petugas_id'   => auth()->id() ?? $model->petugas_id,
                                'status'       => 'digunakan',
                            ]
                        );
                    }
                } catch (\Exception $e) {
                    \Log::warning('Auto generate number on creation failed: ' . $e->getMessage());
                }
            }
        });
    }

    public function getNoSuratAttribute()
    {
        return $this->attributes['nomor_surat'] ?? '';
    }

    public function setNoSuratAttribute($value)
    {
        $this->attributes['nomor_surat'] = $value;
    }

    public function getTemplateSuratIdAttribute()
    {
        return $this->attributes['template_id'] ?? null;
    }

    public function setTemplateSuratIdAttribute($value)
    {
        $this->attributes['template_id'] = $value;
    }

    public function getStatusPengajuanAttribute()
    {
        $status = $this->attributes['status'] ?? 'pending';
        return match ($status) {
            'approved', 'signed' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Pending',
        };
    }

    public function setStatusPengajuanAttribute($value)
    {
        $this->attributes['status'] = match ($value) {
            'Disetujui', 'approved', 'signed' => 'approved',
            'Ditolak', 'rejected' => 'rejected',
            default => 'pending',
        };
    }

    public function getTanggalPengajuanAttribute()
    {
        return isset($this->attributes['created_at']) 
            ? date('Y-m-d', strtotime($this->attributes['created_at'])) 
            : ($this->attributes['tanggal_terbit'] ?? date('Y-m-d'));
    }

    public function setTanggalPengajuanAttribute($value)
    {
        $this->attributes['tanggal_terbit'] = $value;
    }

    public function getTtdOlehPerangkatIdAttribute()
    {
        return $this->attributes['signed_by_perangkat_id'] ?? null;
    }

    public function setTtdOlehPerangkatIdAttribute($value)
    {
        $this->attributes['signed_by_perangkat_id'] = $value;
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_nik', 'nik');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }

    public function templateSurat(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function signedByPerangkat(): BelongsTo
    {
        return $this->belongsTo(PerangkatDesa::class, 'signed_by_perangkat_id');
    }

    public function ttdOlehPerangkat(): BelongsTo
    {
        return $this->belongsTo(PerangkatDesa::class, 'signed_by_perangkat_id');
    }
}
