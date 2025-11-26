<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Department;

class DepartmentTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['parent', 'manager', 'children'];

    public function includeParent(Department $department)
    {
        return $this->item($department->parent, new self($this->request), 'parent');
    }

    public function includeManager(Department $department)
    {
        return $this->item($department->manager, new UserTransformer($this->request), 'manager');
    }

    public function includeChildren(Department $department)
    {
        return $this->collection($department->children, new self($this->request), 'children');
    }
}
