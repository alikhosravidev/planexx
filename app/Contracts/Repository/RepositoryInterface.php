<?php

declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Contracts\Entity\EntityInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?EntityInterface;
    public function create(array $data): EntityInterface;
    public function update(int $id, array $data): EntityInterface;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    // Criteria methods
    public function pushCriteria(CriteriaInterface $criteria): self;
    public function applyCriteria(): self;
}
