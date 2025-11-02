<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\Mappers;

use App\Core\User\DTOs\AddressDTO;
use App\Core\User\Entities\Address;
use App\Core\User\Mappers\AddressMapper;
use Illuminate\Http\Request;
use Tests\UnitTestBase;
use Mockery;

class AddressMapperTest extends UnitTestBase
{
    private AddressMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new AddressMapper();
    }

    public function testFromRequestCreatesDTO(): void
    {
        $requestData = [
            'city_id' => 1,
            'receiver_name' => 'John Doe',
            'receiver_mobile' => '09123456789',
            'address' => '123 Main St',
            'postal_code' => '1234567890',
            'latitude' => '35.6892',
            'longitude' => '51.3890',
        ];

        $request = new Request($requestData);

        // Mock auth()->id()
        Mockery::mock('alias:Illuminate\Support\Facades\Auth')
            ->shouldReceive('id')
            ->andReturn(10);

        $dto = $this->mapper->fromRequest($request);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertEquals(1, $dto->cityId);
        $this->assertEquals('John Doe', $dto->receiverName);
        $this->assertEquals('09123456789', $dto->receiverMobile);
        $this->assertEquals('123 Main St', $dto->address);
        $this->assertEquals('1234567890', $dto->postalCode);
        $this->assertEquals(35.6892, $dto->latitude);
        $this->assertEquals(51.3890, $dto->longitude);
        $this->assertEquals(10, $dto->userId);
    }

    public function testFromRequestForUpdateCreatesDTOWithDefaults(): void
    {
        $requestData = [
            'receiver_name' => 'Jane Doe',
            'address' => '456 Elm St',
        ];

        $request = new Request($requestData);

        $address = new Address([
            'id' => 5,
            'city_id' => 2,
            'receiver_name' => 'Old Name',
            'receiver_mobile' => '09876543210',
            'address' => 'Old Address',
            'postal_code' => '0987654321',
            'latitude' => 36.0,
            'longitude' => 52.0,
            'user_id' => 20,
        ]);

        $dto = $this->mapper->fromRequestForUpdate($request, $address);

        $this->assertInstanceOf(AddressDTO::class, $dto);
        $this->assertEquals(2, $dto->cityId); // from address
        $this->assertEquals('Jane Doe', $dto->receiverName); // from request
        $this->assertEquals('09876543210', $dto->receiverMobile); // from address
        $this->assertEquals('456 Elm St', $dto->address); // from request
        $this->assertEquals('0987654321', $dto->postalCode); // from address
        $this->assertEquals(36.0, $dto->latitude); // from address
        $this->assertEquals(52.0, $dto->longitude); // from address
        $this->assertEquals(20, $dto->userId); // from address
    }
}
