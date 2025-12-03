<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Entity\EntityInterface;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission implements EntityInterface
{
    protected $table = 'core_org_permissions';

    protected $fillable = [
        'name',
        'title',
        'guard_name',
    ];
}
