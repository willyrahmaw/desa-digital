<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmProduk extends Model
{
    protected $table = 'umkm_produk';

    protected $fillable = [
        'pelaku_id',
        'umkm_pelaku_id',
        'nama',
        'deskripsi',
        'harga',
        'foto',
        'kategori_id',
        'umkm_kategori_id',
        'whatsapp',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->whatsapp && $model->pelaku_id) {
                $pelaku = \App\Models\UmkmPelaku::find($model->pelaku_id);
                $model->whatsapp = $pelaku ? $pelaku->no_hp : '081234567890';
            }
            if (!$model->whatsapp) {
                $model->whatsapp = '081234567890';
            }
        });
    }

    public function getUmkmPelakuIdAttribute()
    {
        return $this->attributes['pelaku_id'] ?? null;
    }

    public function setUmkmPelakuIdAttribute($value)
    {
        $this->attributes['pelaku_id'] = $value;
    }

    public function getUmkmKategoriIdAttribute()
    {
        return $this->attributes['kategori_id'] ?? null;
    }

    public function setUmkmKategoriIdAttribute($value)
    {
        $this->attributes['kategori_id'] = $value;
    }

    public function pelaku(): BelongsTo
    {
        return $this->belongsTo(UmkmPelaku::class, 'pelaku_id');
    }

    public function umkmPelaku(): BelongsTo
    {
        return $this->belongsTo(UmkmPelaku::class, 'pelaku_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(UmkmKategori::class, 'kategori_id');
    }

    public function umkmKategori(): BelongsTo
    {
        return $this->belongsTo(UmkmKategori::class, 'kategori_id');
    }
}
