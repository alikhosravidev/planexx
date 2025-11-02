<?php

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;

class ProvinceTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['country'];

    public function includeCountry($province)
    {
        if ($province->country) {
            return $this->item($province->country, new CountryTransformer($this->request), 'country');
        }
    }
}
