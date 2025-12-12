<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Transformers;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class WorkflowStateTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'position' => EnumTransformer::class,
    ];

    protected array $availableIncludes = [
        'defaultAssignee',
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
}
