<?php

declare(strict_types=1);

namespace App\Core\Organization\Observers;

use App\Core\Organization\Entities\User;
use App\Utilities\HashUtility;
use Illuminate\Support\Facades\Hash;

class UserPasswordObserver
{
    public function saving(User $user): void
    {
        if (
            $user->isDirty('password')
            && $user->password !== null
            && ! HashUtility::isBcryptHash($user->password)
        ) {
            $user->password = Hash::make($user->password);
        }
    }
}
