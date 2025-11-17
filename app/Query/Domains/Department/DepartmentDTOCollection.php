<?php

declare(strict_types=1);

namespace App\Query\Domains\Department;

use App\Contracts\BaseCollection;

class DepartmentDTOCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = DepartmentDTO::class;
    }
}
