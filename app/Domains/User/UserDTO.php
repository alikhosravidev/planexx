<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\DataTransferObject;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use App\ValueObjects\NationalCode;

readonly class UserDTO implements DataTransferObject
{
    public function __construct(
        public UserId $id,
        public string $fullName,
        public Mobile $mobile,
        public ?Email $email = null,
        public ?NationalCode $nationalCode = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id->value,
            'full_name'     => $this->fullName,
            'mobile'        => $this->mobile->value,
            'email'         => $this->email?->value,
            'national_code' => $this->nationalCode?->value,
        ];
    }
}
