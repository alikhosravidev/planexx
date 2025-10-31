<?php

declare(strict_types=1);

namespace App\Core\User\Events;

use App\Core\User\Entities\User;

class UserRegistered
{
    public function __construct(
        public User $user
    ) {
    }
}
