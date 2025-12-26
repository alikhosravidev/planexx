<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\TaskPriority;
use App\Domains\User\UserId;
use App\Domains\Workflow\WorkflowId;
use App\Domains\WorkflowState\WorkflowStateId;
use App\ValueObjects\Hours;
use Illuminate\Contracts\Support\Arrayable;

final readonly class TaskDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public WorkflowId $workflowId,
        public UserId $assigneeId,
        public UserId $createdBy,
        public TaskPriority $priority,
        public ?string $description = null,
        public ?WorkflowStateId $currentStateId = null,
        public ?Hours $estimatedHours = null,
        public ?\DateTimeInterface $nextFollowUpDate = null,
    ) {
        if ($this->nextFollowUpDate && $this->nextFollowUpDate <= new \DateTimeImmutable('now')) {
            throw new \InvalidArgumentException('next_follow_up_date must be in the future');
        }
    }

    public function toArray(): array
    {
        return [
            'title'               => $this->title,
            'description'         => $this->description,
            'workflow_id'         => $this->workflowId->value,
            'current_state_id'    => $this->currentStateId?->value,
            'assignee_id'         => $this->assigneeId->value,
            'created_by'          => $this->createdBy->value,
            'priority'            => $this->priority,
            'estimated_hours'     => $this->estimatedHours?->asString(),
            'next_follow_up_date' => $this->nextFollowUpDate?->format('Y-m-d H:i:s'),
        ];
    }
}
