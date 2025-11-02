<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\DTOs;

use App\Core\User\DTOs\AddressDTO;
use Tests\UnitTestBase;

class AddressDTOTest extends UnitTestBase
{
    public function testConstructorSetsProperties(): void
    {
        $dto = new AddressDTO(
            cityId: 1,
            receiverName: 'John Doe',
            receiverMobile: '09123456789',
            address: '123 Main St',
            postalCode: '1234567890',
            latitude: 35.6892,
            longitude: 51.3890,
            userId: 10
        );

        $this->assertEquals(1, $dto->cityId);
        $this->assertEquals('John Doe', $dto->receiverName);
        $this->assertEquals('09123456789', $dto->receiverMobile);
        $this->assertEquals('123 Main St', $dto->address);
        $this->assertEquals('1234567890', $dto->postalCode);
        $this->assertEquals(35.6892, $dto->latitude);
        $this->assertEquals(51.3890, $dto->longitude);
        $this->assertEquals(10, $dto->userId);
    }

    public function testToArrayReturnsCorrectArray(): void
    {
        $dto = new AddressDTO(
            cityId: 1,
            receiverName: 'John Doe',
            receiverMobile: '09123456789',
            address: '123 Main St',
            postalCode: '1234567890',
            latitude: 35.6892,
            longitude: 51.3890,
            userId: 10
        );

        $expected = [
            'city_id' => 1,
            'receiver_name' => 'John Doe',
            'receiver_mobile' => '09123456789',
            'address' => '123 Main St',
            'postal_code' => '1234567890',
            'latitude' => 35.6892,
            'longitude' => 51.3890,
            'user_id' => 10,
        ];

        $this->assertEquals($expected, $dto->toArray());
    }
}
