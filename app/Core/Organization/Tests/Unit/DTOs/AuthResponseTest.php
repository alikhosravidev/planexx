<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Entities\User;
use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\DTOs\AuthToken;
use App\Core\Organization\Services\Auth\DTOs\PasswordConfig;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Services\OTPService\OTPConfig;
use App\Core\Organization\Services\OTPService\OTPResponse;
use App\Query\ValueObjects\Email;
use Tests\PureUnitTestBase;

class AuthResponseTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
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
        $message    = 'Success';
        $identifier = new Identifier('test@example.com', $authConfig);
        $token      = new AuthToken(
            token: 'test-token',
            type: 'Bearer'
        );
        $user = new User([
            'id'    => 1,
            'email' => new Email('test@example.com'),
            'name'  => 'Test User',
        ]);
        $isRegistered = true;
        $nextStep     = 'complete';
        $otpData      = new OTPResponse(
            receiver: 'test@example.com',
            channel: 'email'
        );

        $response = new AuthResponse(
            message     : $message,
            identifier  : $identifier,
            token       : $token,
            user        : $user,
            nextStep    : $nextStep,
            otpData     : $otpData,
            isRegistered: $isRegistered
        );

        $this->assertSame($message, $response->message);
        $this->assertSame($identifier, $response->identifier);
        $this->assertSame($token, $response->token);
        $this->assertSame($user, $response->user);
        $this->assertSame($isRegistered, $response->isRegistered);
        $this->assertSame($nextStep, $response->nextStep);
        $this->assertSame($otpData, $response->otpData);
    }

    public function test_constructs_successfully_with_null_parameters(): void
    {
        $message = 'Error';

        $response = new AuthResponse(message: $message);

        $this->assertSame($message, $response->message);
        $this->assertNull($response->identifier);
        $this->assertNull($response->token);
        $this->assertNull($response->user);
        $this->assertFalse($response->isRegistered);
        $this->assertNull($response->nextStep);
        $this->assertNull($response->otpData);
    }
}
