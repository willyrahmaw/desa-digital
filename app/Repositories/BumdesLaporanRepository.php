<?php

namespace App\Repositories;

use App\Interfaces\BumdesLaporanRepositoryInterface;
use App\Models\BumdesLaporan;

class BumdesLaporanRepository extends BaseRepository implements BumdesLaporanRepositoryInterface
{
    public function __construct(BumdesLaporan $model)
    {
        parent::__construct($model);
    }
}
