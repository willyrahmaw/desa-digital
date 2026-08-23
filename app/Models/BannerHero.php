<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerHero extends Model
{
    protected $table = 'banner_hero';

    protected $fillable = [
        'gambar',
        'judul',
        'subjudul',
        'tag',
        'link_url',
        'button_text',
        'urutan',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'urutan' => 'integer',
    ];
}
