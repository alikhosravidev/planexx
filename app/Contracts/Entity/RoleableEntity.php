<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface RoleableEntity
{
    public function roles(): BelongsToMany;

    public function assignRole(...$roles);

    public function removeRole(...$role);

    public function syncRoles(...$roles);

    public function hasRole($roles, ?string $guard = null): bool;

    public function hasAnyRole(...$roles): bool;

    public function hasAllRoles($roles, ?string $guard = null): bool;

    public function hasExactRoles($roles, ?string $guard = null): bool;
}
