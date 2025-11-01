<?php

namespace App\Core\User\Services;

use App\Core\User\Entities\Address;
use App\Core\User\Repositories\AddressRepository;
use App\Core\User\Repositories\CityRepository;
use App\Core\User\Services\DTOs\AddressDTO;

class AddressService
{
    public function __construct(
        private readonly AddressRepository $addressRepository,
        private readonly CityRepository    $cityRepository,
    ) {
    }

    public function createAddress(AddressDTO $dto): Address
    {
        $data = $dto->toArray();

        if (! isset($data['city_id'])) {
            return $this->addressRepository->create($data);
        }

        $data = $this->setProvinceAndCountry($data);

        return $this->addressRepository->create($data);
    }

    public function updateAddress(Address $address, AddressDTO $dto): Address
    {
        $data = $dto->toArray();

        if (! isset($data['city_id'])) {
            return $this->addressRepository->update($address->id, $data);
        }

        $data = $this->setProvinceAndCountry($data);

        return $this->addressRepository->update($address->id, $data);
    }

    public function deleteAddress(Address $address): bool
    {
        return $this->addressRepository->delete($address->id);
    }

    private function setProvinceAndCountry(array $data): array
    {
        $city = $this->cityRepository->query()
            ->with(['province.country'])
            ->findOrFail($data['city_id']);

        $province = $city->province;
        $data['province_id'] = $province?->id;
        $data['country_id'] = $province?->country?->id;

        return $data;
    }
}
