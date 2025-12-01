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
        return $this->item($department->parent, resolve(self::class), 'parent');
    }

    public function includeUsers(Department $department)
    {
        return $this->item($department->users, resolve(UserTransformer::class), 'users');
    }

    public function includeManager(Department $department)
    {
        return $this->item($department->manager, resolve(UserTransformer::class), 'manager');
    }

    public function includeChildren(Department $department)
    {
        return $this->collection($department->children, resolve(self::class), 'children');
    }
}
