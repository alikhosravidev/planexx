<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\OTPService\Generators;

use App\Core\Organization\Services\OTPService\Contracts\OTPGenerator;
use App\Core\Organization\Services\OTPService\OTPConfig;

class RealGenerator implements OTPGenerator
{
    public function __construct(
        private readonly OTPConfig $config
    ) {
    }

    public function generate(): string
    {
        return implode(
            '',
            array_map(
                static fn () => random_int(0, 9),
                range(1, $this->config->codeLength)
            )
        );
    }
}
