<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Department;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class DepartmentTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['parent', 'manager', 'children', 'users'];

    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,
    ];

    public function includeParent(Department $department)
    {
        return $this->item($department->parent, $this);
    }

    public function includeUsers(Department $department)
    {
        return $this->collection($department->users, resolve(UserTransformer::class));
    }

    public function includeManager(Department $department)
    {
        return $this->item($department->manager, resolve(UserTransformer::class));
    }

    public function includeChildren(Department $department)
    {
        $children = $department->children;

        if ($children->isEmpty()) {
            return $this->null();
        }

        $childTransformer = resolve(self::class);
        $childTransformer->setDefaultIncludes(['children']);

        return $this->collection($children, $childTransformer);
    }
}
