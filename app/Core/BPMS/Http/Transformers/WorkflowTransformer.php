<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Transformers;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\BPMS\Entities\Workflow;
use App\Core\Organization\Http\Transformers\V1\Admin\DepartmentTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\RoleTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;

class WorkflowTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
    ];

    protected array $availableIncludes = [
        'department',
        'owner',
        'allowedRoles',
        'states',
    ];

    public function includeDepartment(Workflow $workflow)
    {
        return $this->itemRelation(
            model: $workflow,
            relationName: 'department',
            transformer: DepartmentTransformer::class,
        );
    }

    public function includeOwner(Workflow $workflow)
    {
        return $this->itemRelation(
            model: $workflow,
            relationName: 'owner',
            transformer: UserTransformer::class,
            foreignKey: 'owner_id',
        );
    }

    public function includeAllowedRoles(Workflow $workflow)
    {
        return $this->collectionRelation(
            model: $workflow,
            relationName: 'allowedRoles',
            transformer: RoleTransformer::class,
        );
    }

    public function includeStates(Workflow $workflow)
    {
        return $this->collectionRelation(
            model: $workflow,
            relationName: 'states',
            transformer: WorkflowStateTransformer::class,
        );
    }
}
