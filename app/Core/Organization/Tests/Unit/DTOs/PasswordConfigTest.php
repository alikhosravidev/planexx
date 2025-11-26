<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Services\Auth\DTOs\PasswordConfig;
use Tests\PureUnitTestBase;

class PasswordConfigTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_valid_data(): void
    {
        $validationRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/';

        $config = new PasswordConfig($validationRegex);

        $this->assertSame($validationRegex, $config->validationRegex);
    }
}
