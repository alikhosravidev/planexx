<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Query\Domains\User\UserId;
use App\Query\Domains\Workflow\WorkflowId;
use App\Query\ValueObjects\ColorHex;
use App\Query\ValueObjects\Slug;
use Illuminate\Contracts\Support\Arrayable;

final readonly class WorkflowStateDTO implements Arrayable
{
    public function __construct(
        public WorkflowId $workflowId,
        public string $name,
        public Slug $slug,
        public ?string $description = null,
        public ?ColorHex $color = null,
        public ?int $order = null,
        public WorkflowStatePosition $position = WorkflowStatePosition::MIDDLE,
        public ?UserId $defaultAssigneeId = null,
        public bool $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'workflow_id'         => $this->workflowId->value,
            'name'                => $this->name,
            'slug'                => (string) $this->slug,
            'description'         => $this->description,
            'color'               => $this->color?->normalized(),
            'order'               => $this->order,
            'position'            => $this->position,
            'default_assignee_id' => $this->defaultAssigneeId?->value,
            'is_active'           => $this->isActive,
        ];
    }
}
