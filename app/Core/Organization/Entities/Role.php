<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Entity\EntityInterface;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements EntityInterface
{
    protected bool $shouldLogActivity = true;

    public const TABLE = 'core_org_roles';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'title',
        'guard_name',
    ];
}
