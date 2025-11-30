<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\DataTransferObject;

readonly class ActiveUserStatsDTO implements DataTransferObject
{
    public function __construct(
        public int $totalCount,
        public int $employeesCount,
        public int $customersCount,
        public int $usersCount,
    ) {
    }

    public function toArray(): array
    {
        return [
            'total_count'     => $this->totalCount,
            'employees_count' => $this->employeesCount,
            'customers_count' => $this->customersCount,
            'users_count'     => $this->usersCount,
        ];
    }
}
