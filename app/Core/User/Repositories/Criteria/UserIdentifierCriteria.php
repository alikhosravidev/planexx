<?php

declare(strict_types=1);

namespace App\Core\User\Repositories\Criteria;

use App\Contracts\Repository\CriteriaInterface;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use Illuminate\Database\Eloquent\Builder;

class UserIdentifierCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly Identifier $identifier
    ) {
    }

    public function apply(Builder $query): Builder
    {
        return $query->where(
            $this->identifier->type->value,
            '=',
            $this->identifier->value
        );
    }
}
