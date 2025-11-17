<?php

declare(strict_types=1);

namespace App\Query\Domains\User;

use App\Query\Contracts\QueryServiceInterface;
use App\Query\Domains\Department\DepartmentId;
use App\Query\ValueObjects\Email;
use App\Query\ValueObjects\Mobile;
use App\Query\ValueObjects\NationalCode;

interface UserQueryService extends QueryServiceInterface
{
    public function getById(UserId $id): UserDTO;

    public function findById(UserId $id): ?UserDTO;

    public function findByIds(UserIdCollection $userIds): UserDTOCollection;

    public function exists(UserId $id): bool;

    public function findByEmail(Email $email): ?UserDTO;

    public function findByMobile(Mobile $mobile): ?UserDTO;

    public function findByNationalCode(NationalCode $nationalCode): ?UserDTO;

    public function getActiveUsers(): UserDTOCollection;

    public function getUsersByRole(string $roleName): UserDTOCollection;

    public function getUsersByDepartment(DepartmentId $departmentId): UserDTOCollection;

    public function countActiveUsers(): int;
}
