<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles {
        roles as spatieRoles;
    }

    public function roles(): BelongsToMany
    {
        return $this->spatieRoles()->withPivot('is_primary');
    }
}
