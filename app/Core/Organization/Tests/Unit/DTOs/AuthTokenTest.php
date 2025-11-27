<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Services\Auth\DTOs\AuthToken;
use Tests\PureUnitTestBase;

class AuthTokenTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $token        = 'access_token';
        $type         = 'bearer';
        $refreshToken = 'refresh_token';

        $authToken = new AuthToken($token, $type, $refreshToken);

        $this->assertSame($token, $authToken->value);
        $this->assertSame($type, $authToken->type);
        $this->assertSame($refreshToken, $authToken->refreshToken);
    }

    public function test_constructs_successfully_without_refresh_token(): void
    {
        $token = 'access_token';
        $type  = 'bearer';

        $authToken = new AuthToken($token, $type);

        $this->assertSame($token, $authToken->value);
        $this->assertSame($type, $authToken->type);
        $this->assertNull($authToken->refreshToken);
    }
}
