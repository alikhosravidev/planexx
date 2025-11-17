<?php

declare(strict_types=1);

namespace App\Core\User\Services\Query;

use App\Core\User\Entities\User;
use App\Core\User\Repositories\UserRepository;
use App\Query\Domains\Department\DepartmentId;
use App\Query\Domains\User\UserDTO;
use App\Query\Domains\User\UserDTOCollection;
use App\Query\Domains\User\UserId;
use App\Query\Domains\User\UserIdCollection;
use App\Query\Domains\User\UserQueryService;
use App\Query\Exceptions\EntityNotFoundException;
use App\Query\ValueObjects\Email;
use App\Query\ValueObjects\Mobile;
use App\Query\ValueObjects\NationalCode;

readonly class UserQueryServiceImplementation implements UserQueryService
{
    public function __construct(
        private UserRepository $repository
    ) {
    }

    public function getById(UserId $id): UserDTO
    {
        $user = $this->repository->find($id->value);

        if (is_null($user)) {
            throw EntityNotFoundException::notFoundId('User', $id->value);
        }

        return $this->toDTO($user);
    }

    public function findById(UserId $id): ?UserDTO
    {
        $user = $this->repository->find($id->value);

        return $user ? $this->toDTO($user) : null;
    }

    public function findByIds(UserIdCollection $userIds): UserDTOCollection
    {
        if ($userIds->isEmpty()) {
            return UserDTOCollection::empty();
        }

        $users = $this->repository
            ->newQuery()
            ->whereIn('id', $userIds->toArray())
            ->get();
        $data = array_map(fn (User $user) => $this->toDTO($user), $users->all());

        return new UserDTOCollection($data);
    }

    public function exists(UserId $id): bool
    {
        return $this->repository
            ->newQuery()
            ->where('id', $id->value)
            ->exists();
    }

    public function findByEmail(Email $email): ?UserDTO
    {
        $user = $this->repository
            ->newQuery()
            ->where('email', $email->value)
            ->first();

        return $user ? $this->toDTO($user) : null;
    }

    public function findByMobile(Mobile $mobile): ?UserDTO
    {
        $user = $this->repository->findByMobile($mobile->value);

        return $user ? $this->toDTO($user) : null;
    }

    public function findByNationalCode(NationalCode $nationalCode): ?UserDTO
    {
        $user = $this->repository
            ->newQuery()
            ->where('national_code', $nationalCode->value)
            ->first();

        return $user ? $this->toDTO($user) : null;
    }

    public function getActiveUsers(): UserDTOCollection
    {
        $users = $this->repository
            ->newQuery()
            ->where('is_active', true)
            ->get();
        $data = array_map(fn (User $user) => $this->toDTO($user), $users->all());

        return new UserDTOCollection($data);
    }

    public function getUsersByRole(string $roleName): UserDTOCollection
    {
        $users = $this->repository
            ->newQuery()
            ->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })
            ->get();
        $data = array_map(fn (User $user) => $this->toDTO($user), $users->all());

        return new UserDTOCollection($data);
    }

    public function getUsersByDepartment(DepartmentId $departmentId): UserDTOCollection
    {
        $users = $this->repository
            ->newQuery()
            ->whereHas('jobPosition', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId->value);
            })
            ->get();
        $data = array_map(fn (User $user) => $this->toDTO($user), $users->all());

        return new UserDTOCollection($data);
    }

    public function countActiveUsers(): int
    {
        return $this->repository
            ->newQuery()
            ->where('is_active', true)
            ->count();
    }

    private function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            new UserId($user->id),
            $user->full_name,
            $user->mobile,
            $user->email,
            $user->national_code,
        );
    }
}
