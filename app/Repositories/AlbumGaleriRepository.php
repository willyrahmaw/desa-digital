<?php

namespace App\Repositories;

use App\Interfaces\AlbumGaleriRepositoryInterface;
use App\Models\AlbumGaleri;

class AlbumGaleriRepository extends BaseRepository implements AlbumGaleriRepositoryInterface
{
    public function __construct(AlbumGaleri $model)
    {
        parent::__construct($model);
    }
}
