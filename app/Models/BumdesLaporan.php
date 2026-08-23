<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BumdesLaporan extends Model
{
    protected $table = 'bumdes_laporan';

    protected $fillable = [
        'unit_id',
        'bumdes_unit_id',
        'jenis',
        'judul',
        'jenis_laporan',
        'file_path',
        'tanggal',
        'tahun',
        'deskripsi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->jenis) {
                $judulLower = strtolower($model->judul ?? '');
                $model->jenis = str_contains($judulLower, 'kegiatan') ? 'kegiatan' : 'keuangan';
            }
            if (!$model->tanggal) {
                $model->tanggal = now()->toDateString();
            }
        });
    }

    public function getBumdesUnitIdAttribute()
    {
        return $this->attributes['unit_id'] ?? null;
    }

    public function setBumdesUnitIdAttribute($value)
    {
        $this->attributes['unit_id'] = $value;
    }

    public function getTahunAttribute()
    {
        if (isset($this->attributes['tanggal'])) {
            return date('Y', strtotime($this->attributes['tanggal']));
        }
        return date('Y');
    }

    public function setTahunAttribute($value)
    {
        $this->attributes['tanggal'] = $value . '-01-01';
    }

    public function getJenisLaporanAttribute()
    {
        return $this->attributes['judul'] ?? '';
    }

    public function setJenisLaporanAttribute($value)
    {
        $this->attributes['judul'] = $value;
        $judulLower = strtolower($value ?? '');
        $this->attributes['jenis'] = str_contains($judulLower, 'kegiatan') ? 'kegiatan' : 'keuangan';
    }

    public function getKeteranganAttribute()
    {
        return $this->attributes['deskripsi'] ?? '';
    }

    public function setKeteranganAttribute($value)
    {
        $this->attributes['deskripsi'] = $value;
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(BumdesUnit::class, 'unit_id');
    }

    public function bumdesUnit(): BelongsTo
    {
        return $this->belongsTo(BumdesUnit::class, 'unit_id');
    }
}
