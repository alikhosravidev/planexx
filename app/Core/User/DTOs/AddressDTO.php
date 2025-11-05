<?php

declare(strict_types=1);

namespace App\Core\User\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class AddressDTO implements Arrayable
{
    public function __construct(
        public int    $userId,
        public string $receiverName,
        public string $receiverMobile,
        public string $address,
        public string $postalCode,
        public float  $latitude,
        public float  $longitude,
        public ?int   $cityId = null,
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
