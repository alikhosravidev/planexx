<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Integration\Services;

use App\Core\User\Entities\User;
use App\Core\User\Services\Auth\AuthService;
use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\AuthRequestDto;
use App\Core\User\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Query\ValueObjects\Email;
use Tests\IntegrationTestBase;

class AuthServiceTest extends IntegrationTestBase
{
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = app(AuthService::class);
    }

    public function test_auth_successfully_authenticates_existing_user(): void
    {
        $email    = 'email@email.com';
        $password = 'password123';
        $user     = User::factory()->create([
            'email'    => new Email($email),
            'password' => $password,
        ]);
        $identifier     = new Identifier($email, app(AuthConfig::class));
        $clientMetadata = new ClientMetadataDto('ip', 'user_agent');
        $authRequestDto = new AuthRequestDto($identifier, $password, $clientMetadata, 'password');

        $response = $this->authService->auth($authRequestDto);

        $this->assertEquals(trans('user::success.welcome_message'), $response->message);
        $this->assertNotNull($response->token);
        $this->assertEquals($user->id, $response->user->id);
    }
}
