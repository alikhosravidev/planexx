<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\OTPService\Contracts;

interface OTPGenerator
{
    public function generate(): string;
}
