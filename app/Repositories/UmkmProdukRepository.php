<?php

namespace App\Repositories;

use App\Interfaces\UmkmProdukRepositoryInterface;
use App\Models\UmkmProduk;

class UmkmProdukRepository extends BaseRepository implements UmkmProdukRepositoryInterface
{
    public function __construct(UmkmProduk $model)
    {
        parent::__construct($model);
    }
}
