<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModelContract;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements BaseModelContract
{
    protected $table = 'core_org_roles';

    protected $fillable = [
        'name',
        'title',
        'guard_name',
    ];
}
