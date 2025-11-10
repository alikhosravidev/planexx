<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\FollowUpType;
use App\Domain\ValueObjects\TaskId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\WorkflowStateId;
use Illuminate\Contracts\Support\Arrayable;

final readonly class FollowUpDTO implements Arrayable
{
    public function __construct(
        public TaskId $taskId,
        public FollowUpType $type,
        public UserId $createdBy,
        public ?string $content = null,
        public ?UserId $newAssigneeId = null,
        public ?WorkflowStateId $newStateId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'task_id'         => $this->taskId->value,
            'type'            => $this->type,
            'content'         => $this->content,
            'created_by'      => $this->createdBy->value,
            'new_assignee_id' => $this->newAssigneeId?->value,
            'new_state_id'    => $this->newStateId?->value,
        ];
    }
}
