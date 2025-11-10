<?php

declare(strict_types=1);

namespace App\Core\BPMS\DTOs;

use App\Domain\ValueObjects\DepartmentId;
use App\Domain\ValueObjects\Slug;
use App\Domain\ValueObjects\UserId;
use Illuminate\Contracts\Support\Arrayable;

final readonly class WorkflowDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public ?Slug $slug = null,
        public ?string $description = null,
        public ?DepartmentId $departmentId = null,
        public ?UserId $workflowOwnerId = null,
        public ?UserId $createdBy = null,
        public bool $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'              => $this->name,
            'slug'              => $this->slug?->__toString(),
            'description'       => $this->description,
            'department_id'     => $this->departmentId?->value,
            'workflow_owner_id' => $this->workflowOwnerId?->value,
            'created_by'        => $this->createdBy?->value,
            'is_active'         => $this->isActive,
        ];
    }
}
