<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Repositories\Criteria\UserAccessibleWorkflowsCriteria;

class WorkflowRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
        'slug' => 'like',
    ];

    public array $filterableFields = [
        'department_id' => '=',
        'is_active'     => '=',
    ];

    public array $sortableFields = [
        'id', 'name', 'slug', 'created_at', 'updated_at',
    ];

    /**
     * Custom Filters Configuration.
     *
     * Available filters:
     * - user_accessible: Filters workflows that the current user has access to
     *   Parameters: [?int $userId, bool $includeInactive = false]
     *
     * @var array<string, class-string>
     */
    protected array $customFilters = [
        'user_accessible' => UserAccessibleWorkflowsCriteria::class,
    ];

    public function model(): string
    {
        return Workflow::class;
    }
}
