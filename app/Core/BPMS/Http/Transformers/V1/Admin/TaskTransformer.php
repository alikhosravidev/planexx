<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\BPMS\Entities\Task;
use App\Core\FileManager\Http\Transformers\V1\Admin\FileTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class TaskTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'priority' => EnumTransformer::class,
    ];

    protected array $availableIncludes = [
        'workflow',
        'currentState',
        'assignee',
        'creator',
        'followUps',
        'attachments',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'current_state_order' => fn (Task $task) => $task->current_state_order,
            'progress_percentage' => fn (Task $task) => $task->progress_percentage,
            'remaining_days'      => fn (Task $task) => $task->remaining_days,
            'morph_class'         => fn (Task $task) => $task->getMorphClass(),
            'morph_class'         => fn (Task $task) => $task->getMorphClass(),
        ];
    }

    public function includeWorkflow(Task $task)
    {
        return $this->itemRelation(
            model: $task,
            relationName: 'workflow',
            transformer: WorkflowTransformer::class,
        );
    }

    public function includeCurrentState(Task $task)
    {
        return $this->itemRelation(
            model: $task,
            relationName: 'currentState',
            transformer: WorkflowStateTransformer::class,
            foreignKey: 'current_state_id',
        );
    }

    public function includeAssignee(Task $task)
    {
        return $this->itemRelation(
            model: $task,
            relationName: 'assignee',
            transformer: UserTransformer::class,
            foreignKey: 'assignee_id',
        );
    }

    public function includeCreator(Task $task)
    {
        return $this->itemRelation(
            model: $task,
            relationName: 'creator',
            transformer: UserTransformer::class,
            foreignKey: 'created_by',
        );
    }

    public function includeFollowUps(Task $task)
    {
        return $this->collectionRelation(
            model: $task,
            relationName: 'followUps',
            transformer: FollowUpTransformer::class,
        );
    }

    public function includeAttachments(Task $task)
    {
        return $this->collectionRelation(
            model: $task,
            relationName: 'attachments',
            transformer: FileTransformer::class,
        );
    }
}
