<?php

declare(strict_types=1);

namespace App\Core\Organization\events;

use App\Core\Organization\Entities\User;

class UserRegistered
{
    public function __construct(
        public User $user
    ) {
    }
}
