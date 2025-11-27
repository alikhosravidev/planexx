<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\BaseCollection;

class UserIdCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = UserId::class;
    }
}
