<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories\Criteria;

use App\Contracts\Repository\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Filters users who have at least one of the specified roles.
 *
 * Usage:
 * - $repository->applyCustomFilter('has_roles', [['admin', 'manager']])
 * - $repository->applyCustomFilter('has_roles', [[1, 2, 3]]) // Role IDs
 */
class UserHasRolesCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly array $roles = []
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if (empty($this->roles)) {
            return $query;
        }

        return $query->whereHas('roles', function (Builder $roleQuery) {
            $roleQuery->where(function (Builder $q) {
                $q->whereIn('id', $this->roles);
            });
        });
    }
}
