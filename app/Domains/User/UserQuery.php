<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\QueryInterface;
use App\Domains\Department\DepartmentId;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use App\ValueObjects\NationalCode;

interface UserQuery extends QueryInterface
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
