<?php

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;

class AddressTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['city', 'province', 'country'];

    public function includeCity($address)
    {
        return $this->item($address->city, new CityTransformer($this->request), 'city');
    }

    public function includeProvince($address)
    {
        if ($address->province) {
            return $this->item($address->province, new ProvinceTransformer($this->request), 'province');
        }
    }

    public function includeCountry($address)
    {
        if ($address->country) {
            return $this->item($address->country, new CountryTransformer($this->request), 'country');
        }
    }
}
