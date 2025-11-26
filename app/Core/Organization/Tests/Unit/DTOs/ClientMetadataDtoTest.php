<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Services\Auth\DTOs\ClientMetadataDto;
use Tests\PureUnitTestBase;

class ClientMetadataDtoTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $ipAddress   = '192.168.1.1';
        $userAgent   = 'Mozilla/5.0';
        $fingerprint = 'abc123';

        $dto = new ClientMetadataDto($ipAddress, $userAgent, $fingerprint);

        $this->assertSame($ipAddress, $dto->ipAddress);
        $this->assertSame($userAgent, $dto->userAgent);
        $this->assertSame($fingerprint, $dto->fingerprint);
    }

    public function test_constructs_successfully_with_null_parameters(): void
    {
        $ipAddress = '192.168.1.1';

        $dto = new ClientMetadataDto($ipAddress, null, null);

        $this->assertSame($ipAddress, $dto->ipAddress);
        $this->assertNull($dto->userAgent);
        $this->assertNull($dto->fingerprint);
    }
}
