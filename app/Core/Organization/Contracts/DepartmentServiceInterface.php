<?php

declare(strict_types=1);

namespace App\Core\Organization\Contracts;

use App\Core\Organization\Entities\Department;
use App\Domains\Department\DepartmentDTO;

interface DepartmentServiceInterface
{
    public function create(DepartmentDTO $dto): Department;

    public function update(Department $department, DepartmentDTO $dto): Department;

    public function delete(Department $department): bool;
}
