<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories\Criteria;

use App\Contracts\Repository\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Filters workflows that the current user has access to.
 *
 * A user has access to a workflow if User has one of the workflow's allowed roles, OR
 */
class UserAccessibleWorkflowsCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly ?int $userId = null,
    ) {
    }

    public function apply(Builder $query): Builder
    {
        $userId = $this->userId ?? auth()->id();

        // If no user, return empty result
        if (!$userId) {
            return $query->whereRaw('1 = 0');
        }

        $query->where(function (Builder $q) use ($userId) {
            $q->whereHas('allowedRoles', function (Builder $roleQuery) use ($userId) {
                $roleQuery->whereHas('users', function (Builder $userQuery) use ($userId) {
                    $userQuery->where('id', '=', $userId);
                });
            });
        });

        return $query;
    }
}
