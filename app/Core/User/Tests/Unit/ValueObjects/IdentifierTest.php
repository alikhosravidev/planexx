<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\ValueObjects;

use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\PasswordConfig;
use App\Core\User\Services\Auth\Enums\IdentifierType;
use App\Core\User\Services\Auth\Exceptions\IdentifierException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\OTPConfig;
use Tests\UnitTestBase;

class IdentifierTest extends UnitTestBase
{
    private AuthConfig $authConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authConfig = new AuthConfig(
            otpConfig: new OTPConfig(
                isEnabled: true,
                codeLength: 6,
                expiresInMinutes: 10,
                pattern: 'numeric'
            ),
            passwordConfig: new PasswordConfig(
                validationRegex: '/^[a-zA-Z0-9_]+$/'
            ),
            authGuard: 'web',
            authTokenName: 'token',
            usernameValidationRegex: '/^[a-zA-Z0-9_]+$/',
            usernameValidationMessage: 'Invalid username'
        );
    }

    public function test_constructs_successfully_with_valid_email(): void
    {
        $email = 'test@example.com';

        $identifier = new Identifier($email, $this->authConfig);

        $this->assertEquals(IdentifierType::Email, $identifier->type);
        $this->assertEquals(strtolower($email), $identifier->value);
    }

    public function test_constructs_successfully_with_valid_mobile(): void
    {
        $mobile = '09123456789';

        $identifier = new Identifier($mobile, $this->authConfig);

        $this->assertEquals(IdentifierType::Mobile, $identifier->type);
        $this->assertEquals($mobile, $identifier->value); // Assuming no transform for this mobile
    }

    public function test_constructs_successfully_with_valid_username(): void
    {
        $username = 'testuser';

        $identifier = new Identifier($username, $this->authConfig);

        $this->assertEquals(IdentifierType::Username, $identifier->type);
        $this->assertEquals(strtolower($username), $identifier->value);
    }

    public function test_throws_exception_for_invalid_email(): void
    {
        $invalidEmail = 'invalid@';

        $this->expectException(IdentifierException::class);
        $this->expectExceptionMessage(
            trans('user::errors.email_invalid')
        );

        new Identifier($invalidEmail, $this->authConfig);
    }

    public function test_throws_exception_for_invalid_mobile(): void
    {
        $invalidMobile = '123456789'; // Not valid mobile

        $this->expectException(IdentifierException::class);
        $this->expectExceptionMessage(
            trans('user::errors.mobile_invalid')
        );

        new Identifier($invalidMobile, $this->authConfig);
    }

    public function test_throws_exception_for_invalid_username(): void
    {
        $invalidUsername = 'invalid-username';

        $this->expectException(IdentifierException::class);
        $this->expectExceptionMessage(
            trans('user::errors.username_format_invalid')
        );

        new Identifier($invalidUsername, $this->authConfig);
    }

    public function test_equals_returns_true_for_same_type_and_value(): void
    {
        $identifier1 = new Identifier('test@example.com', $this->authConfig);
        $identifier2 = new Identifier('test@example.com', $this->authConfig);

        $this->assertTrue($identifier1->equals($identifier2));
    }

    public function test_equals_returns_false_for_different_type(): void
    {
        $identifier1 = new Identifier('test@example.com', $this->authConfig);
        $identifier2 = new Identifier('testuser', $this->authConfig);

        $this->assertFalse($identifier1->equals($identifier2));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $identifier1 = new Identifier('test@example.com', $this->authConfig);
        $identifier2 = new Identifier('other@example.com', $this->authConfig);

        $this->assertFalse($identifier1->equals($identifier2));
    }

    public function test_to_string_returns_value(): void
    {
        $email      = 'test@example.com';
        $identifier = new Identifier($email, $this->authConfig);

        $this->assertEquals(strtolower($email), (string) $identifier);
    }

    public function test_from_string_constructs_with_valid_identifier(): void
    {
        $email = 'test@example.com';

        $identifier = Identifier::fromString($email);

        $this->assertEquals(IdentifierType::Email, $identifier->type);
        $this->assertEquals(strtolower($email), $identifier->value);
    }

    public function test_from_string_throws_exception_for_empty_string(): void
    {
        $this->expectException(IdentifierException::class);
        $this->expectExceptionMessage(
            trans('user::errors.identifier_required')
        );

        Identifier::fromString('');
    }
}
