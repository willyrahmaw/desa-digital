<?php

namespace App\Repositories;

use App\Interfaces\RwRepositoryInterface;
use App\Models\Rw;

class RwRepository extends BaseRepository implements RwRepositoryInterface
{
    public function __construct(Rw $model)
    {
        parent::__construct($model);
    }
}
