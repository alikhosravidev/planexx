<?php

namespace App\Contracts\Repository;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseCriteria implements CriteriaInterface
{
    protected array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    abstract public function apply(Builder $query): Builder;
}
