<?php

declare(strict_types=1);

namespace App\Query\Domains\Department;

use App\Query\Contracts\QueryServiceInterface;
use App\Query\Domains\User\UserId;

interface DepartmentQueryService extends QueryServiceInterface
{
    public function getById(DepartmentId $id): DepartmentDTO;

    public function findById(DepartmentId $id): ?DepartmentDTO;

    public function getActiveDepartments(): DepartmentDTOCollection;

    public function getUserDepartment(UserId $userId): ?DepartmentDTO;

    public function getSubDepartments(DepartmentId $parentId): DepartmentDTOCollection;
}
