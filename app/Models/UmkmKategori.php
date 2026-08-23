<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmkmKategori extends Model
{
    protected $table = 'umkm_kategori';
    protected $fillable = ['nama', 'slug'];

    public function produks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'kategori_id');
    }
}
