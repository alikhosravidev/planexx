<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Department;

class DepartmentTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['parent', 'manager', 'children', 'users'];

    public function includeParent(Department $department)
    {
        return $this->item($department->parent, resolve(self::class));
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

        return $this->collection($children, resolve(self::class));
    }
}
