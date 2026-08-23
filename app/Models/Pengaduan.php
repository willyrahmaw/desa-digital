<?php

namespace App\Models;

use App\Traits\HasAesEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use SoftDeletes, HasAesEncryption;

    protected $table = 'pengaduan';

    protected array $encrypted = [
        'pelapor_nik',
    ];

    protected $fillable = [
        'nomor_tiket',
        'pelapor_nik',
        'telepon',
        'email',
        'judul',
        'kategori',
        'isi',
        'lokasi',
        'status',
        'balasan',
        'lampiran',
    ];

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'pelapor_nik', 'nik');
    }
}
