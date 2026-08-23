<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use App\Traits\HasAesEncryption;
use App\Helpers\AesSecurity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penduduk extends Model
{
    use SoftDeletes, HasAuditLog, HasAesEncryption;

    protected $table = 'penduduk';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected array $encrypted = [
        'nik',
        'no_kk',
    ];

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama_id',
        'status_kawin_id',
        'pendidikan_id',
        'pekerjaan_id',
        'alamat',
        'dusun_id',
        'rw_id',
        'rt_id',
        'nomor_hp',
        'email',
        'foto',
        'qr_code',
        'status_tinggal_id',
        'kewarganegaraan_id',
        'golongan_darah_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getMaskedNikAttribute(): string
    {
        return AesSecurity::mask($this->nik);
    }

    public function getMaskedNoKkAttribute(): string
    {
        return AesSecurity::mask($this->no_kk);
    }

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'no_kk', 'no_kk');
    }

    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function statusKawin(): BelongsTo
    {
        return $this->belongsTo(StatusKawin::class, 'status_kawin_id');
    }

    public function pendidikan(): BelongsTo
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id');
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

    public function statusTinggal(): BelongsTo
    {
        return $this->belongsTo(StatusTinggal::class, 'status_tinggal_id');
    }

    public function kewarganegaraan(): BelongsTo
    {
        return $this->belongsTo(Kewarganegaraan::class, 'kewarganegaraan_id');
    }

    public function golonganDarah(): BelongsTo
    {
        return $this->belongsTo(GolonganDarah::class, 'golongan_darah_id');
    }

    public function dataSosial(): HasOne
    {
        return $this->hasOne(DataSosial::class, 'penduduk_nik', 'nik');
    }
}
