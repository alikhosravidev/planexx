<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

/**
 * @property \Illuminate\Database\Eloquent\Collection<Role> $roles
 * @property \Illuminate\Database\Eloquent\Collection<Role> $primaryRoles
 */
trait HasRoles
{
    use SpatieHasRoles {
        roles as spatieRoles;
    }

    public function getArrayableRelations(): array
    {
        $relations = parent::getArrayableRelations();

        unset($relations['primaryRoles']);

        return $relations;
    }

    public function roles(): BelongsToMany
    {
        return $this->spatieRoles()->withPivot('is_primary');
    }

    public function primaryRoles(): BelongsToMany
    {
        return $this->roles()->wherePivot('is_primary', true);
    }
}
