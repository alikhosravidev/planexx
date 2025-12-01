<?php

declare(strict_types=1);

namespace App\Core\Organization\Listeners;

use App\Core\Organization\events\UserUpdated;

class OnUserUpdated
{
    public function handle(UserUpdated $event): void
    {
        // hook for side effects on user update
    }
}
