<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class ForwardTaskDTO implements Arrayable
{
    public function __construct(
        public int $newAssigneeId,
        public int $actorId,
        public ?string $note = null,
        public ?int $nextStateId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'new_assignee_id' => $this->newAssigneeId,
            'actor_id'        => $this->actorId,
            'note'            => $this->note,
            'next_state_id'   => $this->nextStateId,
        ];
    }
}
