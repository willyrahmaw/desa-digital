<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apbdes extends Model
{
    protected $table = 'apbdes';

    protected $fillable = [
        'tahun',
        'tipe',
        'sub_kategori',
        'kategori',
        'jumlah',
        'realisasi',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'realisasi' => 'decimal:2',
    ];

    public function getAnggaranAttribute()
    {
        return $this->attributes['jumlah'] ?? 0;
    }

    public function getNamaItemAttribute()
    {
        return $this->attributes['kategori'] ?? '';
    }

    public function getKategoriFormattedAttribute()
    {
        return ucfirst($this->attributes['tipe'] ?? 'pendapatan');
    }
}
