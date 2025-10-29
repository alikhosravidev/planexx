<?php

namespace App\Contracts\Repository;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    public function apply(Builder $query): Builder;
}
