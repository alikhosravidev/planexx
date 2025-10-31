<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\DTOs;

use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\PasswordConfig;
use App\Core\User\Services\OTPService\OTPConfig;
use Tests\PureUnitTestBase;

class AuthConfigTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_valid_data(): void
    {
        $otpConfig = new OTPConfig(
            isEnabled: true,
            codeLength: 6,
            expiresInMinutes: 10,
            pattern: 'numeric'
        );
        $passwordConfig = new PasswordConfig(
            validationRegex: '/^[a-zA-Z0-9]+$/'
        );
        $authGuard                 = 'web';
        $authTokenName             = 'api_token';
        $usernameValidationRegex   = '/^[a-zA-Z0-9]+$/';
        $usernameValidationMessage = 'Invalid username';

        $config = new AuthConfig(
            otpConfig: $otpConfig,
            passwordConfig: $passwordConfig,
            authGuard: $authGuard,
            authTokenName: $authTokenName,
            usernameValidationRegex: $usernameValidationRegex,
            usernameValidationMessage: $usernameValidationMessage
        );

        $this->assertSame($otpConfig, $config->otpConfig);
        $this->assertSame($passwordConfig, $config->passwordConfig);
        $this->assertSame($authGuard, $config->authGuard);
        $this->assertSame($authTokenName, $config->authTokenName);
        $this->assertSame($usernameValidationRegex, $config->usernameValidationRegex);
        $this->assertSame($usernameValidationMessage, $config->usernameValidationMessage);
    }
}
