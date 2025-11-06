<?php

declare(strict_types=1);

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\Address;

class AddressTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['city', 'province', 'country'];

    public function includeCity(Address $address)
    {
        return $this->item($address->city, new CityTransformer($this->request), 'city');
    }

    public function includeProvince(Address $address)
    {
        return $this->item($address->province, new ProvinceTransformer($this->request), 'province');
    }

    public function includeCountry(Address $address)
    {
        return $this->item($address->country, new CountryTransformer($this->request), 'country');
    }
}
