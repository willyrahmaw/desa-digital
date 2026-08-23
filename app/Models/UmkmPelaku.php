<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmkmPelaku extends Model
{
    protected $table = 'umkm_pelaku';

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'pelaku_id');
    }
}
