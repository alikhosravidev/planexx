<?php

declare(strict_types=1);

namespace App\Domains\Role;

use App\Contracts\DataTransferObject;

final readonly class RoleDTO implements DataTransferObject
{
    public function __construct(
        public string $name,
        public ?string $title = null,
        public string $guardName = 'web',
        public array $permissions = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'title'      => $this->title,
            'guard_name' => $this->guardName,
        ];
    }
}
