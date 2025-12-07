<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Role;
use App\Services\Transformer\FieldTransformers\DateTimeTransformer;

class RoleTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'created_at' => DateTimeTransformer::class,
        'updated_at' => DateTimeTransformer::class,
    ];

    protected array $availableIncludes = ['permissions'];

    public function includePermissions(Role $role)
    {
        return $this->collectionRelation(
            model: $role,
            relationName: 'permissions',
            transformer: PermissionTransformer::class,
        );
    }

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'users_count'       => fn (Role $role) => $role->users_count ?? $role->users()->count(),
            'permissions_count' => fn (Role $role) => $role->permissions_count ?? $role->permissions()->count(),
        ];
    }
}
