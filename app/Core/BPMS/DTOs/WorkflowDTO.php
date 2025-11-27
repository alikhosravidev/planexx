<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Domains\Department\DepartmentId;
use App\Domains\User\UserId;
use App\ValueObjects\Slug;
use Illuminate\Contracts\Support\Arrayable;

final readonly class WorkflowDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public ?Slug $slug = null,
        public ?string $description = null,
        public ?DepartmentId $departmentId = null,
        public ?UserId $ownerId = null,
        public ?UserId $createdBy = null,
        public bool $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'          => $this->name,
            'slug'          => $this->slug?->__toString(),
            'description'   => $this->description,
            'department_id' => $this->departmentId?->value,
            'owner_id'      => $this->ownerId?->value,
            'created_by'    => $this->createdBy?->value,
            'is_active'     => $this->isActive,
        ];
    }
}
