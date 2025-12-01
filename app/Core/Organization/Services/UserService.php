<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\Organization\Entities\User;
use App\Core\Organization\events\UserCreated;
use App\Core\Organization\events\UserUpdated;
use App\Core\Organization\Repositories\UserRepository;
use App\Domains\User\UserUpsertDTO;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function create(UserUpsertDTO $dto): User
    {
        /** @var User $user */
        $user = $this->userRepository->create($dto->toArray());

        if ($dto->departmentId) {
            $user->departments()->sync([$dto->departmentId => ['is_primary' => true]]);
        }

        if ($dto->password) {
            $user->changePassword($dto->password)->save();
        }

        event(new UserCreated($user));

        return $user;
    }

    public function update(User $user, UserUpsertDTO $dto): User
    {
        /** @var User $updated */
        $updated = $this->userRepository->update($user->id, $dto->toArray());

        if ($dto->departmentId !== null) {
            $updated->departments()->sync([$dto->departmentId => ['is_primary' => true]]);
        }

        if ($dto->password) {
            $updated->changePassword($dto->password)->save();
        }

        event(new UserUpdated($updated));

        return $updated;
    }

    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user->id);
    }
}
