<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\OTPService\Generators;

use App\Core\Organization\Services\OTPService\Contracts\OTPGenerator;

class FakeGenerator implements OTPGenerator
{
    public const TEST_CODE = '1234';

    public function generate(): string
    {
        return self::TEST_CODE;
    }
}
