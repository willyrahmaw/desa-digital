<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Dusun extends Model
{
    protected $table = 'dusun';
    protected $fillable = ['nama'];

    public function rws(): HasMany
    {
        return $this->hasMany(Rw::class, 'dusun_id');
    }

    public function rts(): HasManyThrough
    {
        return $this->hasManyThrough(Rt::class, Rw::class, 'dusun_id', 'rw_id');
    }
}
