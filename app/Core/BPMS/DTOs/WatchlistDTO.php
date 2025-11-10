<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Core\BPMS\Enums\WatchStatus;
use App\Domain\ValueObjects\TaskId;
use App\Domain\ValueObjects\UserId;
use Illuminate\Contracts\Support\Arrayable;

final readonly class WatchlistDTO implements Arrayable
{
    public function __construct(
        public TaskId $taskId,
        public UserId $watcherId,
        public WatchStatus $watchStatus,
        public ?string $watchReason = null,
        public ?string $comment = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'task_id'      => $this->taskId->value,
            'watcher_id'   => $this->watcherId->value,
            'watch_status' => $this->watchStatus,
            'watch_reason' => $this->watchReason,
            'comment'      => $this->comment,
        ];
    }
}
