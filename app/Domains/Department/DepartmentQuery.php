<?php

declare(strict_types=1);

namespace App\Domains\Department;

use App\Contracts\QueryInterface;
use App\Domains\User\UserId;

interface DepartmentQuery extends QueryInterface
{
    public function getById(DepartmentId $id): DepartmentDTO;

    public function findById(DepartmentId $id): ?DepartmentDTO;

    public function getActiveDepartments(): DepartmentDTOCollection;

    public function getUserDepartment(UserId $userId): ?DepartmentDTO;

    public function getSubDepartments(DepartmentId $parentId): DepartmentDTOCollection;
}
