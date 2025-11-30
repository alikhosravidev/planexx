<?php

declare(strict_types=1);

namespace App\Core\Organization\events;

use App\Contracts\BusinessEvent;
use App\Core\Organization\Entities\User;

class UserRegistered implements BusinessEvent
{
    public function __construct(
        public User $user
    ) {
    }
}
