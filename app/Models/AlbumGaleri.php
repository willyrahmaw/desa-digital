<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlbumGaleri extends Model
{
    protected $table = 'album_galeri';
    protected $fillable = ['nama', 'deskripsi'];

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoGaleri::class, 'album_id');
    }

    public function fotoGaleri(): HasMany
    {
        return $this->hasMany(FotoGaleri::class, 'album_id');
    }
}
