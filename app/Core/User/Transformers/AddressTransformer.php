<?php

namespace App\Core\User\Transformers;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\Address;
use Illuminate\Http\Request;

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
