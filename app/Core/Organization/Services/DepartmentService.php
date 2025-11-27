<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\Organization\Contracts\DepartmentServiceInterface;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Domains\Department\DepartmentDTO;

readonly class DepartmentService implements DepartmentServiceInterface
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
    ) {
    }

    public function create(DepartmentDTO $dto): Department
    {
        $data = $dto->toArray();

        return $this->departmentRepository->create($data);
    }

    public function update(Department $department, DepartmentDTO $dto): Department
    {
        $data = $dto->toArray();

        return $this->departmentRepository->update($department->id, $data);
    }

    public function delete(Department $department): bool
    {
        return $this->departmentRepository->delete($department->id);
    }
}
