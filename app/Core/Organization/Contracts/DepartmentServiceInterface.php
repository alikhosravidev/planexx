<?php

declare(strict_types=1);

namespace App\Core\Organization\Contracts;

use App\Core\Organization\DTOs\DepartmentDTO;
use App\Core\Organization\Entities\Department;

interface DepartmentServiceInterface
{
    public function create(DepartmentDTO $dto): Department;

    public function update(Department $department, DepartmentDTO $dto): Department;

    public function delete(Department $department): bool;
}
