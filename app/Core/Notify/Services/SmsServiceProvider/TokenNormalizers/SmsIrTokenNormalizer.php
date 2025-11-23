<?php

declare(strict_types = 1);

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

namespace App\Core\Notify\Services\SmsServiceProvider\TokenNormalizers;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\TokenNormalizer;

class SmsIrTokenNormalizer implements TokenNormalizer
{
    public function __construct(
        private readonly int $limitVariableCount
    ) {
    }

    public function normalize(array $tokens): array
    {
        $finalTokens = [];
        for ($counter = 0; $counter < $this->limitVariableCount; ++$counter) {
            if (!isset($tokens[$counter])) {
                continue;
            }
            $variableNumber = $counter + 1;
            $finalTokens[]  = [
                'name'  => "variable{$variableNumber}",
                'value' => $tokens[$counter],
            ];
        }

        return $finalTokens;
    }
}
