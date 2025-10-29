<?php

namespace App\Contracts\Repository;

use App\Contracts\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?BaseModel;
    public function create(array $data): BaseModel;
    public function update(int $id, array $data): BaseModel;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    // Criteria methods
    public function pushCriteria(CriteriaInterface $criteria): self;
    public function applyCriteria(): self;
}
