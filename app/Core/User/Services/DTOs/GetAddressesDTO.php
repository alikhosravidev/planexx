<?php

namespace App\Core\User\Services\DTOs;

final class GetAddressesDTO
{
    public function __construct(
        public readonly ?int $userId = null,
        public readonly ?string $search = null,
        public readonly string $orderBy = 'desc',
    ) {}
}
