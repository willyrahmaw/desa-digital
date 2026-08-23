<?php

namespace App\Repositories;

use App\Interfaces\UmkmPelakuRepositoryInterface;
use App\Models\UmkmPelaku;

class UmkmPelakuRepository extends BaseRepository implements UmkmPelakuRepositoryInterface
{
    public function __construct(UmkmPelaku $model)
    {
        parent::__construct($model);
    }
}
