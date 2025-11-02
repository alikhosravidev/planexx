<?php

namespace App\Core\User\Services;

use App\Core\User\DTOs\AddressDTO;
use App\Core\User\Entities\Address;
use App\Core\User\Repositories\AddressRepository;
use App\Core\User\Repositories\CityRepository;

class AddressService
{
    public function __construct(
        private readonly AddressRepository $addressRepository,
        private readonly CityRepository    $cityRepository,
    ) {
    }

    public function create(AddressDTO $dto): Address
    {
        $data = $dto->toArray();

        if (! isset($data['city_id'])) {
            return $this->addressRepository->create($data);
        }

        $data = $this->setProvinceAndCountry($data);

        return $this->addressRepository->create($data);
    }

    public function update(Address $address, AddressDTO $dto): Address
    {
        $data = $dto->toArray();

        if (! isset($data['city_id'])) {
            return $this->addressRepository->update($address->id, $data);
        }

        $data = $this->setProvinceAndCountry($data);

        return $this->addressRepository->update($address->id, $data);
    }

    public function delete(Address $address): bool
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
