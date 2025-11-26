<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\ChangePasswordRequestDto;
use App\Core\Organization\Services\Auth\DTOs\PasswordConfig;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Services\OTPService\OTPConfig;
use Tests\PureUnitTestBase;

class ChangePasswordRequestDtoTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_valid_data(): void
    {
        $authConfig = new AuthConfig(
            otpConfig: new OTPConfig(
                isEnabled: true,
                codeLength: 6,
                expiresInMinutes: 10,
                pattern: 'numeric'
            ),
            passwordConfig: new PasswordConfig(
                validationRegex: '/^[a-zA-Z0-9]+$/'
            ),
            authGuard: 'web',
            authTokenName: 'api_token',
            usernameValidationRegex: '/^[a-zA-Z0-9]+$/',
            usernameValidationMessage: 'Invalid username'
        );
        $identifier  = new Identifier('test@example.com', $authConfig);
        $oldPassword = 'old_pass';
        $newPassword = 'new_pass';

        $dto = new ChangePasswordRequestDto($identifier, $oldPassword, $newPassword);

        $this->assertSame($identifier, $dto->identifier);
        $this->assertSame($oldPassword, $dto->password);
        $this->assertSame($newPassword, $dto->newPassword);
    }
}
