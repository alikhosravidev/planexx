<?php

declare(strict_types=1);

namespace App\Query\Domains\User;

use App\Query\Contracts\DataTransferObject;
use App\Query\ValueObjects\Email;
use App\Query\ValueObjects\Mobile;
use App\Query\ValueObjects\NationalCode;

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
