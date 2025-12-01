<?php

declare(strict_types=1);

namespace App\Core\Organization\Listeners;

use App\Core\Organization\events\UserCreated;

class OnUserCreated
{
    public function handle(UserCreated $event): void
    {
        // hook for side effects on user creation
    }
}
