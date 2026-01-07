<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Domains\User\UserId;
use App\Domains\Workflow\WorkflowId;
use App\ValueObjects\ColorHex;
use Illuminate\Contracts\Support\Arrayable;

final readonly class WorkflowStateDTO implements Arrayable
{
    public function __construct(
        public WorkflowId $workflowId,
        public string $name,
        public ?string $description = null,
        public ?ColorHex $color = null,
        public ?int $order = null,
        public WorkflowStatePosition $position = WorkflowStatePosition::Middle,
        public ?UserId $defaultAssigneeId = null,
        public bool $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'workflow_id'         => $this->workflowId->value,
            'name'                => $this->name,
            'description'         => $this->description,
            'color'               => $this->color?->normalized(),
            'order'               => $this->order,
            'position'            => $this->position,
            'default_assignee_id' => $this->defaultAssigneeId?->value,
            'is_active'           => $this->isActive,
        ];
    }
}
