<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class AddNoteDTO implements Arrayable
{
    public function __construct(
        public string $content,
        public int $actorId,
        public ?\DateTimeInterface $nextFollowUpDate = null,
        public mixed $attachment = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'content'             => $this->content,
            'actor_id'            => $this->actorId,
            'next_follow_up_date' => $this->nextFollowUpDate?->format('Y-m-d H:i:s'),
            'has_attachment'      => $this->attachment !== null,
        ];
    }
}
