<?php

namespace App\Repositories;

use App\Interfaces\KartuKeluargaRepositoryInterface;
use App\Models\KartuKeluarga;

class KartuKeluargaRepository extends BaseRepository implements KartuKeluargaRepositoryInterface
{
    public function __construct(KartuKeluarga $model)
    {
        parent::__construct($model);
    }
}
