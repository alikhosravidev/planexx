<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Mappers;

use App\Core\Organization\DTOs\AddressDTO;
use App\Core\Organization\Entities\Address;
use App\Core\Organization\Mappers\AddressMapper;
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
        $cityId         = 1;
        $receiverName   = 'John Doe';
        $receiverMobile = '09123456789';
        $address        = '123 Main St';
        $postalCode     = '1234567890';
        $latitude       = 35.6892;
        $longitude      = 51.3890;
        $userId         = 10;

        $requestData = [
            'city_id'         => $cityId,
            'receiver_name'   => $receiverName,
            'receiver_mobile' => $receiverMobile,
            'address'         => $address,
            'postal_code'     => $postalCode,
            'latitude'        => (string) $latitude,
            'longitude'       => (string) $longitude,
        ];

        $request = $this->makeRequestWithUser($requestData, $userId);

        $dto = $this->mapper->fromRequest($request);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertSame($cityId, $dto->cityId);
        $this->assertSame($receiverName, $dto->receiverName);
        $this->assertSame($receiverMobile, $dto->receiverMobile);
        $this->assertSame($address, $dto->address);
        $this->assertSame($postalCode, $dto->postalCode);
        $this->assertEquals($latitude, $dto->latitude);
        $this->assertEquals($longitude, $dto->longitude);
        $this->assertSame($userId, $dto->userId);
    }

    public function test_from_request_for_update_creates_dto_with_defaults(): void
    {
        $updatedReceiverName  = 'Jane Doe';
        $updatedAddress       = '456 Elm St';
        $entityId             = 5;
        $entityCityId         = 2;
        $entityReceiverName   = 'Old Name';
        $entityReceiverMobile = '09876543210';
        $entityAddress        = 'Old Address';
        $entityPostalCode     = '0987654321';
        $entityLatitude       = 36.0;
        $entityLongitude      = 52.0;
        $entityUserId         = 20;
        $ignoredUserId        = 999;

        $requestData = [
            'receiver_name' => $updatedReceiverName,
            'address'       => $updatedAddress,
        ];

        $request = $this->makeRequestWithUser($requestData, $ignoredUserId);

        $address = new Address([
            'id'              => $entityId,
            'city_id'         => $entityCityId,
            'receiver_name'   => $entityReceiverName,
            'receiver_mobile' => $entityReceiverMobile,
            'address'         => $entityAddress,
            'postal_code'     => $entityPostalCode,
            'latitude'        => $entityLatitude,
            'longitude'       => $entityLongitude,
            'user_id'         => $entityUserId,
        ]);

        $dto = $this->mapper->fromRequestForUpdate($request, $address);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertSame($entityCityId, $dto->cityId);
        $this->assertSame($updatedReceiverName, $dto->receiverName);
        $this->assertSame($entityReceiverMobile, $dto->receiverMobile);
        $this->assertSame($updatedAddress, $dto->address);
        $this->assertSame($entityPostalCode, $dto->postalCode);
        $this->assertEquals($entityLatitude, $dto->latitude);
        $this->assertEquals($entityLongitude, $dto->longitude);
        $this->assertSame($entityUserId, $dto->userId);
    }
}
