<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function all(): Collection;
    
    public function paginate(int $perPage = 15, array $relations = [], array $filters = []): LengthAwarePaginator;
    
    public function find(mixed $id): ?Model;
    
    public function create(array $attributes): Model;
    
    public function update(mixed $id, array $attributes): bool;
    
    public function delete(mixed $id): bool;
    
    public function restore(mixed $id): bool;
}
