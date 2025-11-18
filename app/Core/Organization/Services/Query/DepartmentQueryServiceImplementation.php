<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Query;

use App\Core\Organization\Repositories\DepartmentRepository;
use App\Query\Domains\Department\DepartmentDTO;
use App\Query\Domains\Department\DepartmentDTOCollection;
use App\Query\Domains\Department\DepartmentId;
use App\Query\Domains\Department\DepartmentQueryService;
use App\Query\Domains\User\UserId;

readonly class DepartmentQueryServiceImplementation implements DepartmentQueryService
{
    public function __construct(
        private DepartmentRepository $repository,
    ) {
    }

    public function getById(DepartmentId $id): DepartmentDTO
    {
        // TODO: Implement getById() method.
    }

    public function findById(DepartmentId $id): ?DepartmentDTO
    {
        // TODO: Implement findById() method.
    }

    public function getActiveDepartments(): DepartmentDTOCollection
    {
        // TODO: Implement getActiveDepartments() method.
    }

    public function getUserDepartment(UserId $userId): ?DepartmentDTO
    {
        // TODO: Implement getUserDepartment() method.
    }

    public function getSubDepartments(DepartmentId $parentId): DepartmentDTOCollection
    {
        // TODO: Implement getSubDepartments() method.
    }
}
