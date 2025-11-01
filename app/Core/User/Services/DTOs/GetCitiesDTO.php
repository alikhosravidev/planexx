<?php

namespace App\Core\User\Services\DTOs;

final class GetCitiesDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly string $orderBy = 'desc',
    ) {}
}
