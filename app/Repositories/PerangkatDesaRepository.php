<?php

namespace App\Repositories;

use App\Interfaces\PerangkatDesaRepositoryInterface;
use App\Models\PerangkatDesa;

class PerangkatDesaRepository extends BaseRepository implements PerangkatDesaRepositoryInterface
{
    public function __construct(PerangkatDesa $model)
    {
        parent::__construct($model);
    }
}
