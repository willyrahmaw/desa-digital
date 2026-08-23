<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoGaleri extends Model
{
    protected $table = 'video_galeri';
    protected $fillable = ['judul', 'url', 'deskripsi'];
}
