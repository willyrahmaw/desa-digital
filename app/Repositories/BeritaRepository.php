<?php

namespace App\Repositories;

use App\Interfaces\BeritaRepositoryInterface;
use App\Models\Berita;

class BeritaRepository extends BaseRepository implements BeritaRepositoryInterface
{
    public function __construct(Berita $model)
    {
        parent::__construct($model);
    }
}
