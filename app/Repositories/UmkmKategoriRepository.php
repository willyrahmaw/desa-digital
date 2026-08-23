<?php

namespace App\Repositories;

use App\Interfaces\UmkmKategoriRepositoryInterface;
use App\Models\UmkmKategori;

class UmkmKategoriRepository extends BaseRepository implements UmkmKategoriRepositoryInterface
{
    public function __construct(UmkmKategori $model)
    {
        parent::__construct($model);
    }
}
