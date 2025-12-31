<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\Organization\Http\Transformers\V1\Admin\RoleTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class WorkflowStateTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'position' => EnumTransformer::class,
    ];

    protected array $availableIncludes = [
        'defaultAssignee',
        'allowedRoles',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'is_final' => fn (WorkflowState $state) => $state->position->isFinal(),
        ];
    }

    public function includeDefaultAssignee(WorkflowState $state)
    {
        return $this->itemRelation(
            model: $state,
            relationName: 'defaultAssignee',
            transformer: UserTransformer::class,
            foreignKey: 'default_assignee_id',
        );
    }

    public function includeAllowedRoles(WorkflowState $state)
    {
        return $this->collectionRelation(
            model: $state,
            relationName: 'allowedRoles',
            transformer: RoleTransformer::class,
        );
    }
}
