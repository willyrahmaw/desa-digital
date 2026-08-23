<?php

namespace App\Repositories;

use App\Interfaces\DusunRepositoryInterface;
use App\Models\Dusun;

class DusunRepository extends BaseRepository implements DusunRepositoryInterface
{
    public function __construct(Dusun $model)
    {
        parent::__construct($model);
    }
}
