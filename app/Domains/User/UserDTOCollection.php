<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\BaseCollection;

class UserDTOCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = UserDTO::class;
    }
}
