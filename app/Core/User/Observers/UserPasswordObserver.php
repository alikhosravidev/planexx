<?php

declare(strict_types=1);

namespace App\Core\User\Observers;

use App\Core\User\Entities\User;
use App\Utilities\HashUtilities;
use Illuminate\Support\Facades\Hash;

class UserPasswordObserver
{
    public function saving(User $user): void
    {
        if (
            $user->isDirty('password')
            && $user->password !== null
            && ! HashUtilities::isBcryptHash($user->password)
        ) {
            $user->password = Hash::make($user->password);
        }
    }
}
