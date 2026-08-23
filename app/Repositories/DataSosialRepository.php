<?php

namespace App\Repositories;

use App\Interfaces\DataSosialRepositoryInterface;
use App\Models\DataSosial;

class DataSosialRepository extends BaseRepository implements DataSosialRepositoryInterface
{
    public function __construct(DataSosial $model)
    {
        parent::__construct($model);
    }
}
