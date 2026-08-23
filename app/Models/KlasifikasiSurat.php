<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiSurat extends Model
{
    protected $table = 'klasifikasi_surat';

    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'deskripsi',
        'status',
        'urutan',
    ];
}
