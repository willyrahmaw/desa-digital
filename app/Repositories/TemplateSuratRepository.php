<?php

namespace App\Repositories;

use App\Interfaces\TemplateSuratRepositoryInterface;
use App\Models\TemplateSurat;

class TemplateSuratRepository extends BaseRepository implements TemplateSuratRepositoryInterface
{
    public function __construct(TemplateSurat $model)
    {
        parent::__construct($model);
    }
}
