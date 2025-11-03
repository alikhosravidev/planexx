<?php

declare(strict_types=1);

namespace App\Core\User\Observers;

use App\Core\User\Entities\User;

class UserFullNameObserver
{
    public function saving(User $user): void
    {
        if (empty($user->full_name)) {
            $firstName       = $user->first_name ?? '';
            $lastName        = $user->last_name  ?? '';
            $user->full_name = trim("$firstName $lastName");
        }
    }
}
