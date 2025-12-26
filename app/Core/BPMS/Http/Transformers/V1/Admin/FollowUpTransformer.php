<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\BPMS\Entities\FollowUp;
use App\Core\FileManager\Http\Transformers\V1\Admin\FileTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class FollowUpTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,
    ];

    protected array $availableIncludes = [
        'creator',
        'previousAssignee',
        'newAssignee',
        'previousState',
        'newState',
        'attachments',
    ];

    protected array $defaultIncludes = [
        'creator',
    ];

    public function includeCreator(FollowUp $followUp)
    {
        return $this->itemRelation(
            model: $followUp,
            relationName: 'creator',
            transformer: UserTransformer::class,
            foreignKey: 'created_by',
        );
    }

    public function includePreviousAssignee(FollowUp $followUp)
    {
        return $this->itemRelation(
            model: $followUp,
            relationName: 'previousAssignee',
            transformer: UserTransformer::class,
            foreignKey: 'previous_assignee_id',
        );
    }

    public function includeNewAssignee(FollowUp $followUp)
    {
        return $this->itemRelation(
            model: $followUp,
            relationName: 'newAssignee',
            transformer: UserTransformer::class,
            foreignKey: 'new_assignee_id',
        );
    }

    public function includePreviousState(FollowUp $followUp)
    {
        return $this->itemRelation(
            model: $followUp,
            relationName: 'previousState',
            transformer: WorkflowStateTransformer::class,
            foreignKey: 'previous_state_id',
        );
    }

    public function includeNewState(FollowUp $followUp)
    {
        return $this->itemRelation(
            model: $followUp,
            relationName: 'newState',
            transformer: WorkflowStateTransformer::class,
            foreignKey: 'new_state_id',
        );
    }

    public function includeAttachments(FollowUp $followUp)
    {
        return $this->collectionRelation(
            model: $followUp,
            relationName: 'attachments',
            transformer: FileTransformer::class,
        );
    }
}
