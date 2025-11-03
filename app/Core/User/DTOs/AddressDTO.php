<?php

declare(strict_types=1);

namespace App\Core\User\DTOs;

final readonly class AddressDTO
{
    public function __construct(
        public ?int    $cityId,
        public string $receiverName,
        public string $receiverMobile,
        public string $address,
        public string $postalCode,
        public float  $latitude,
        public float  $longitude,
        public int    $userId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'city_id'         => $this->cityId,
            'receiver_name'   => $this->receiverName,
            'receiver_mobile' => $this->receiverMobile,
            'address'         => $this->address,
            'postal_code'     => $this->postalCode,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'user_id'         => $this->userId,
        ];
    }
}
