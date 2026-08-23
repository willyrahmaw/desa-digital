<?php

namespace App\Repositories;

use App\Interfaces\SuratRepositoryInterface;
use App\Models\Surat;

class SuratRepository extends BaseRepository implements SuratRepositoryInterface
{
    public function __construct(Surat $model)
    {
        parent::__construct($model);
    }
}
