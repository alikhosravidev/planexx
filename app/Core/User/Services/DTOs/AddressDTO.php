<?php

declare(strict_types=1);

namespace App\Core\User\Services\DTOs;

use App\Services\AddressValidationService;

final class AddressDTO
{
    public function __construct(
        public readonly int $cityId,
        public readonly string $receiverName,
        public readonly string $receiverMobile,
        public readonly string $address,
        public readonly string $postalCode,
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly int $userId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->cityId,
            'receiver_name' => $this->receiverName,
            'receiver_mobile' => $this->receiverMobile,
            'address' => $this->address,
            'postal_code' => $this->postalCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'user_id' => $this->userId,
        ];
    }
}
