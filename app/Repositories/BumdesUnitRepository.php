<?php

namespace App\Repositories;

use App\Interfaces\BumdesUnitRepositoryInterface;
use App\Models\BumdesUnit;

class BumdesUnitRepository extends BaseRepository implements BumdesUnitRepositoryInterface
{
    public function __construct(BumdesUnit $model)
    {
        parent::__construct($model);
    }
}
