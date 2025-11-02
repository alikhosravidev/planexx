<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Integration\Services;

use App\Core\User\DTOs\AddressDTO;
use App\Core\User\Entities\Address;
use App\Core\User\Repositories\AddressRepository;
use App\Core\User\Repositories\CityRepository;
use App\Core\User\Services\AddressService;
use Tests\IntegrationTestBase;

class AddressServiceTest extends IntegrationTestBase
{
    private AddressService $service;
    private AddressRepository $addressRepository;
    private CityRepository $cityRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addressRepository = app(AddressRepository::class);
        $this->cityRepository = app(CityRepository::class);
        $this->service = new AddressService(
            $this->addressRepository,
            $this->cityRepository
        );
    }

    public function testCreateWithoutCityId(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $dto = new AddressDTO(
            cityId: null,
            receiverName: 'John Doe',
            receiverMobile: '09123456789',
            address: '123 Main St',
            postalCode: '1234567890',
            latitude: 35.6892,
            longitude: 51.3890,
            userId: $user->id
        );

        $address = $this->service->create($dto);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('John Doe', $address->receiver_name);
        $this->assertNull($address->province_id);
        $this->assertNull($address->country_id);
    }

    public function testCreateWithCityIdSetsProvinceAndCountry(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        // Create country, province, city
        $country = \App\Core\User\Entities\Country::create(['name' => 'Test Country']);
        $province = \App\Core\User\Entities\Province::create(['name' => 'Test Province', 'country_id' => $country->id]);
        $city = \App\Core\User\Entities\City::create(['name' => 'Test City', 'province_id' => $province->id]);

        $dto = new AddressDTO(
            cityId: $city->id,
            receiverName: 'John Doe',
            receiverMobile: '09123456789',
            address: '123 Main St',
            postalCode: '1234567890',
            latitude: 35.6892,
            longitude: 51.3890,
            userId: $user->id
        );

        $address = $this->service->create($dto);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($province->id, $address->province_id);
        $this->assertEquals($country->id, $address->country_id);
    }

    public function testUpdateWithoutCityId(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $address = \App\Core\User\Entities\Address::create([
            'city_id' => 1,
            'receiver_name' => 'Old Name',
            'receiver_mobile' => '09123456789',
            'address' => 'Old Address',
            'postal_code' => '1234567890',
            'latitude' => 35.0,
            'longitude' => 51.0,
            'user_id' => $user->id,
        ]);

        $dto = new AddressDTO(
            cityId: null,
            receiverName: 'Updated Name',
            receiverMobile: '09876543210',
            address: 'Updated Address',
            postalCode: '0987654321',
            latitude: 36.0,
            longitude: 52.0,
            userId: $address->user_id
        );

        $updatedAddress = $this->service->update($address, $dto);

        $this->assertInstanceOf(Address::class, $updatedAddress);
        $this->assertEquals('Updated Name', $updatedAddress->receiver_name);
        $this->assertNull($updatedAddress->province_id);
    }

    public function testUpdateWithCityIdSetsProvinceAndCountry(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $address = \App\Core\User\Entities\Address::create([
            'city_id' => 1,
            'receiver_name' => 'Old Name',
            'receiver_mobile' => '09123456789',
            'address' => 'Old Address',
            'postal_code' => '1234567890',
            'latitude' => 35.0,
            'longitude' => 51.0,
            'user_id' => $user->id,
        ]);

        $country = \App\Core\User\Entities\Country::create(['name' => 'Test Country']);
        $province = \App\Core\User\Entities\Province::create(['name' => 'Test Province', 'country_id' => $country->id]);
        $city = \App\Core\User\Entities\City::create(['name' => 'Test City', 'province_id' => $province->id]);

        $dto = new AddressDTO(
            cityId: $city->id,
            receiverName: 'Updated Name',
            receiverMobile: '09876543210',
            address: 'Updated Address',
            postalCode: '0987654321',
            latitude: 36.0,
            longitude: 52.0,
            userId: $address->user_id
        );

        $updatedAddress = $this->service->update($address, $dto);

        $this->assertInstanceOf(Address::class, $updatedAddress);
        $this->assertEquals($province->id, $updatedAddress->province_id);
        $this->assertEquals($country->id, $updatedAddress->country_id);
    }

    public function testDelete(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $address = \App\Core\User\Entities\Address::create([
            'city_id' => 1,
            'receiver_name' => 'John Doe',
            'receiver_mobile' => '09123456789',
            'address' => '123 Main St',
            'postal_code' => '1234567890',
            'latitude' => 35.0,
            'longitude' => 51.0,
            'user_id' => $user->id,
        ]);

        $result = $this->service->delete($address);

        $this->assertTrue($result);
        $this->assertNull(Address::find($address->id));
    }
}
