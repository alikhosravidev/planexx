<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\Organization\Services\OTPService;

class OTPConfig
{
    public function __construct(
        public readonly bool $isEnabled,
        public readonly int $codeLength,
        public readonly int $expiresInMinutes,
        public readonly int|string $pattern,
    ) {
    }
}
