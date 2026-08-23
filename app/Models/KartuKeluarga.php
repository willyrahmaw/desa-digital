<?php

namespace App\Models;

use App\Traits\HasAesEncryption;
use App\Helpers\AesSecurity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    use HasAesEncryption;

    protected $table = 'kartu_keluarga';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected array $encrypted = [
        'no_kk',
        'kepala_keluarga_nik',
    ];

    protected $fillable = [
        'no_kk',
        'alamat',
        'dusun_id',
        'rw_id',
        'rt_id',
        'kepala_keluarga_nik',
    ];

    public function getMaskedNoKkAttribute(): string
    {
        return AesSecurity::mask($this->no_kk);
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class, 'rw_id');
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_nik', 'nik');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'no_kk', 'no_kk');
    }
}
