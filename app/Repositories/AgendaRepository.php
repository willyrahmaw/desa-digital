<?php

namespace App\Repositories;

use App\Interfaces\AgendaRepositoryInterface;
use App\Models\Agenda;

class AgendaRepository extends BaseRepository implements AgendaRepositoryInterface
{
    public function __construct(Agenda $model)
    {
        parent::__construct($model);
    }
}
