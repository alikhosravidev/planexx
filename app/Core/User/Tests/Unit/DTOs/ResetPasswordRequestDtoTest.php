<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\DTOs;

use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\User\Services\Auth\DTOs\PasswordConfig;
use App\Core\User\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\OTPConfig;
use Tests\PureUnitTestBase;

class ResetPasswordRequestDtoTest extends PureUnitTestBase
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
        $identifier     = new Identifier('test@example.com', $authConfig);
        $code           = 'reset_token';
        $password       = 'new_pass';
        $repeatPassword = 'new_pass';
        $clientMetadata = new ClientMetadataDto(
            ipAddress: '127.0.0.1',
            userAgent: 'TestAgent',
            fingerprint: 'test-fingerprint'
        );

        $dto = new ResetPasswordRequestDto($identifier, $code, $password, $repeatPassword, $clientMetadata);

        $this->assertSame($identifier, $dto->identifier);
        $this->assertSame($code, $dto->code);
        $this->assertSame($password, $dto->password);
        $this->assertSame($repeatPassword, $dto->repeatPassword);
        $this->assertSame($clientMetadata, $dto->clientMetadata);
    }
}
