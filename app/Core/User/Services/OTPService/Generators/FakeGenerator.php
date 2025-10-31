<?php

declare(strict_types=1);

namespace App\Core\User\Services\OTPService\Generators;

use App\Core\User\Services\OTPService\Contracts\OTPGenerator;

class FakeGenerator implements OTPGenerator
{
    public const TEST_CODE = '123456';

    public function generate(): string
    {
        return self::TEST_CODE;
    }
}
