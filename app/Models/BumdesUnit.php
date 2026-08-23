<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BumdesUnit extends Model
{
    protected $table = 'bumdes_unit';

    protected $fillable = [
        'nama',
        'deskripsi',
        'penanggung_jawab',
        'ketua',
        'status',
    ];

    public function getKetuaAttribute()
    {
        return $this->attributes['penanggung_jawab'] ?? '';
    }

    public function setKetuaAttribute($value)
    {
        $this->attributes['penanggung_jawab'] = $value;
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(BumdesLaporan::class, 'unit_id');
    }
}
