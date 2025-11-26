<?php

declare(strict_types=1);

namespace App\Core\Organization\Observers;

use App\Core\Organization\Entities\User;

class UserFullNameObserver
{
    public function saving(User $user): void
    {
        if (empty($user->full_name)) {
            $firstName       = $user->first_name ?? '';
            $lastName        = $user->last_name  ?? '';
            $fullName        = trim("$firstName $lastName");
            $user->full_name = !empty($fullName) ? $fullName : null;
        }
    }
}
