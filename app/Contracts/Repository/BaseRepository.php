<?php

declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Contracts\Model\BaseModel;
use App\Contracts\Model\BaseModelContract;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected BaseModelContract $model;
    protected Builder $query;
    protected Collection $criteria;

    public array $fieldSearchable = [];
    public array $sortableFields  = [];

    public function __construct()
    {
        $this->criteria = new Collection();
        $this->makeModel();
    }

    abstract public function model(): string;

    public function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof BaseModelContract) {
            $baseModelClass = BaseModelContract::class;

            throw new Exception("Class {$this->model()} must be an instance of {$baseModelClass}");
        }

        $this->model = $model;
        $this->query = $this->model->newQuery();

        return $this->model;
    }

    public function resetModel(): void
    {
        $this->makeModel();
    }

    // Basic CRUD Operations
    public function all(): Collection
    {
        $this->applyCriteria();
        $result = $this->query->get();
        $this->resetModel();

        return $result;
    }

    public function find(int $id): ?BaseModel
    {
        $this->applyCriteria();
        $result = $this->query->find($id);
        $this->resetModel();

        return $result;
    }

    public function first(): ?BaseModel
    {
        $this->applyCriteria();
        $result = $this->query->first();
        $this->resetModel();

        return $result;
    }

    public function findOrFail(int $id): ?BaseModel
    {
        $this->applyCriteria();
        $result = $this->query->findOrFail($id);
        $this->resetModel();

        return $result;
    }

    public function create(array $data): BaseModel
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): BaseModel
    {
        $this->model->where('id', $id)->update($data);

        return $this->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $this->applyCriteria();
        $result = $this->query->paginate($perPage);
        $this->resetModel();

        return $result;
    }

    // Additional useful methods
    public function findBy(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    public function findOneBy(string $field, $value): ?BaseModel
    {
        return $this->model->where($field, $value)->first();
    }

    public function count(): int
    {
        $this->applyCriteria();
        $result = $this->query->count();
        $this->resetModel();

        return $result;
    }

    // Criteria Methods
    public function pushCriteria(CriteriaInterface $criteria): self
    {
        $this->criteria->push($criteria);

        return $this;
    }

    public function applyCriteria(): self
    {
        $this->query = $this->model->newQuery();

        foreach ($this->criteria as $criteria) {
            if ($criteria instanceof CriteriaInterface) {
                $this->query = $criteria->apply($this->query);
            }
        }

        $this->criteria = new Collection();

        return $this;
    }


    // Query Builder Access
    public function newQuery(): Builder
    {
        $this->applyCriteria();

        return $this->query;
    }

    public function query(): Builder
    {
        return $this->newQuery();
    }
}
