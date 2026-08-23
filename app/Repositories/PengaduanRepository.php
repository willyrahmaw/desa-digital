<?php

namespace App\Repositories;

use App\Interfaces\PengaduanRepositoryInterface;
use App\Models\Pengaduan;

class PengaduanRepository extends BaseRepository implements PengaduanRepositoryInterface
{
    public function __construct(Pengaduan $model)
    {
        parent::__construct($model);
    }
}
