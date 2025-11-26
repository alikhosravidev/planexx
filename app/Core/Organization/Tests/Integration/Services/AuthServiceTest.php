<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Integration\Services;

use App\Core\Organization\Entities\User;
use App\Core\Organization\Services\Auth\AuthService;
use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\AuthRequestDto;
use App\Core\Organization\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Query\ValueObjects\Email;
use Tests\IntegrationTestBase;

class
AuthServiceTest extends IntegrationTestBase
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

        $this->assertEquals(trans('Organization::success.welcome_message'), $response->message);
        $this->assertNotNull($response->token);
        $this->assertEquals($user->id, $response->user->id);
    }
}
