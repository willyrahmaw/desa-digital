<?php

namespace App\Repositories;

use App\Interfaces\ApbdesRepositoryInterface;
use App\Models\Apbdes;

class ApbdesRepository extends BaseRepository implements ApbdesRepositoryInterface
{
    public function __construct(Apbdes $model)
    {
        parent::__construct($model);
    }
}
