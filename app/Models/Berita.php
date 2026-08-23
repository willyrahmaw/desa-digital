<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'cover_image',
        'status',
        'views',
        'user_id',
        'kategori_berita_id',
    ];

    public function getStatusAttribute($value)
    {
        return ($value === 'published' || $value === 'Publikasi') ? 'Publikasi' : 'Draft';
    }

    public function setStatusAttribute($value)
    {
        $valueLower = strtolower($value);
        if ($valueLower === 'publikasi' || $valueLower === 'published') {
            $this->attributes['status'] = 'published';
        } else {
            $this->attributes['status'] = 'draft';
        }
    }

    public function getKontenAttribute()
    {
        return $this->attributes['isi'] ?? '';
    }

    public function setKontenAttribute($value)
    {
        $this->attributes['isi'] = $value;
    }

    public function getGambarAttribute()
    {
        return $this->attributes['cover_image'] ?? '';
    }

    public function setGambarAttribute($value)
    {
        $this->attributes['cover_image'] = $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
    }

    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
    }

    public function komentars(): HasMany
    {
        return $this->hasMany(Komentar::class, 'berita_id');
    }
}
