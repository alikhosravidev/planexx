<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\TaskAction;
use Illuminate\Contracts\Support\Arrayable;

final readonly class UpdateTaskActionDTO implements Arrayable
{
    public function __construct(
        public TaskAction $action,
        public ?TaskDTO $taskDTO = null,
        public ?AddNoteDTO $addNoteDTO = null,
        public ?ForwardTaskDTO $forwardTaskDTO = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'action'            => $this->action->value,
            'task_data'         => $this->taskDTO?->toArray(),
            'add_note_data'     => $this->addNoteDTO?->toArray(),
            'forward_task_data' => $this->forwardTaskDTO?->toArray(),
        ];
    }

    public function isEdit(): bool
    {
        return $this->action->isEdit();
    }

    public function isAddNote(): bool
    {
        return $this->action->isAddNote();
    }

    public function isForward(): bool
    {
        return $this->action->isForward();
    }
}
