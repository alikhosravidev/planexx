<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\Mappers;

use App\Core\User\DTOs\AddressDTO;
use App\Core\User\Entities\Address;
use App\Core\User\Mappers\AddressMapper;
use Illuminate\Http\Request;
use Tests\UnitTestBase;

class AddressMapperTest extends UnitTestBase
{
    private AddressMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new AddressMapper();
    }

    private function makeRequestWithUser(array $data, int $userId): Request
    {
        return new class ($data, $userId) extends Request {
            private array $payload;
            private int $uid;

            public function __construct(array $payload, int $uid)
            {
                $this->payload = $payload;
                $this->uid     = $uid;
            }

            public function input($key = null, $default = null)
            {
                if ($key === null) {
                    return $this->payload;
                }

                return $this->payload[$key] ?? $default;
            }

            public function user($guard = null)
            {
                return (object) ['id' => $this->uid];
            }
        };
    }

    public function test_from_request_creates_dto(): void
    {
        $requestData = [
            'city_id'         => 1,
            'receiver_name'   => 'John Doe',
            'receiver_mobile' => '09123456789',
            'address'         => '123 Main St',
            'postal_code'     => '1234567890',
            'latitude'        => '35.6892',
            'longitude'       => '51.3890',
        ];

        $request = $this->makeRequestWithUser($requestData, 10);

        $dto = $this->mapper->fromRequest($request);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertSame(1, $dto->cityId);
        $this->assertSame('John Doe', $dto->receiverName);
        $this->assertSame('09123456789', $dto->receiverMobile);
        $this->assertSame('123 Main St', $dto->address);
        $this->assertSame('1234567890', $dto->postalCode);
        $this->assertEquals(35.6892, $dto->latitude);
        $this->assertEquals(51.3890, $dto->longitude);
        $this->assertSame(10, $dto->userId);
    }

    public function test_from_request_for_update_creates_dto_with_defaults(): void
    {
        $requestData = [
            'receiver_name' => 'Jane Doe',
            'address'       => '456 Elm St',
        ];

        $request = $this->makeRequestWithUser($requestData, 999); // userId is ignored in update

        $address = new Address([
            'id'              => 5,
            'city_id'         => 2,
            'receiver_name'   => 'Old Name',
            'receiver_mobile' => '09876543210',
            'address'         => 'Old Address',
            'postal_code'     => '0987654321',
            'latitude'        => 36.0,
            'longitude'       => 52.0,
            'user_id'         => 20,
        ]);

        $dto = $this->mapper->fromRequestForUpdate($request, $address);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertSame(2, $dto->cityId); // from address
        $this->assertSame('Jane Doe', $dto->receiverName); // from request
        $this->assertSame('09876543210', $dto->receiverMobile); // from address
        $this->assertSame('456 Elm St', $dto->address); // from request
        $this->assertSame('0987654321', $dto->postalCode); // from address
        $this->assertEquals(36.0, $dto->latitude); // from address
        $this->assertEquals(52.0, $dto->longitude); // from address
        $this->assertSame(20, $dto->userId); // from address
    }
}
