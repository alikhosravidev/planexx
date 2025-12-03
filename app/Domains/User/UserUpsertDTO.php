<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Contracts\DataTransferObject;
use App\Core\Organization\Enums\GenderEnum;
use App\Core\Organization\Enums\UserTypeEnum;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use App\ValueObjects\NationalCode;
use Carbon\Carbon;

final readonly class UserUpsertDTO implements DataTransferObject
{
    public function __construct(
        public string $fullName,
        public Mobile $mobile,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?Email $email = null,
        public ?NationalCode $nationalCode = null,
        public ?GenderEnum $gender = null,
        public ?Carbon $birthDate = null,
        public ?UserTypeEnum $userType = null,
        public ?bool $isActive = null,
        public ?string $password = null,
        public ?int $directManagerId = null,
        public ?int $departmentId = null,
        public ?Carbon $employmentDate = null,
        public ?string $employeeCode = null,
        public ?string $imageUrl = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'full_name'         => $this->fullName,
            'first_name'        => $this->firstName,
            'last_name'         => $this->lastName,
            'mobile'            => $this->mobile->value,
            'email'             => $this->email?->value,
            'national_code'     => $this->nationalCode?->value,
            'gender'            => $this->gender?->value,
            'birth_date'        => $this->birthDate?->format('Y-m-d H:i:s'),
            'user_type'         => $this->userType->value,
            'is_active'         => $this->isActive,
            'direct_manager_id' => $this->directManagerId,
            'image_url'         => $this->imageUrl,
            'employment_date'   => $this->employmentDate?->format('Y-m-d H:i:s'),
            'employee_code'     => $this->employeeCode,
        ];
    }
}
