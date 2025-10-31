<?php

declare(strict_types=1);

namespace Tests;

use App\Core\User\Entities\User;
use Illuminate\Support\Facades\DB;

trait HasActor
{
    public User $user;

    public function actingAsClient(?User $user = null, string $positionName = 'user'): self
    {
        if (null === $user) {
            $user = $this->createUser($positionName);
        }
        $this->user = $user;

        return $this->actingAs($this->user);
    }

    public function actingAsAdmin(?User $user = null): self
    {
        if (null === $user) {
            $user = $this->createPlatformOwner();
        }
        $this->user = $user;

        return $this->actingAs($this->user);
    }

    public function createPlatformOwner(): User
    {
        return $this->createUser('platform_owner');
    }

    public function createUser(string $positionName = 'user', array $userData = []): User
    {
        $factory = User::factory();

        if (!empty($userData)) {
            $factory = $factory->state($userData);
        }

        DB::beginTransaction();

        try {
            $user = $factory
                ->verified()
                ->withPosition($positionName)
                ->create()
                ->refresh();

            DB::commit();

            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

}
