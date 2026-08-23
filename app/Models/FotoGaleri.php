<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoGaleri extends Model
{
    protected $table = 'foto_galeri';
    protected $fillable = ['album_id', 'file_path', 'judul', 'deskripsi'];

    public function album(): BelongsTo
    {
        return $this->belongsTo(AlbumGaleri::class, 'album_id');
    }
}
