<?php

namespace App\Repositories;

use App\Interfaces\RtRepositoryInterface;
use App\Models\Rt;

class RtRepository extends BaseRepository implements RtRepositoryInterface
{
    public function __construct(Rt $model)
    {
        parent::__construct($model);
    }
}
