<?php

namespace App\Repositories;

use App\Interfaces\PendudukRepositoryInterface;
use App\Models\Penduduk;

class PendudukRepository extends BaseRepository implements PendudukRepositoryInterface
{
    public function __construct(Penduduk $model)
    {
        parent::__construct($model);
    }
}
