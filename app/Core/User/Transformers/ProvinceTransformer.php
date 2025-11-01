<?php

namespace App\Core\User\Transformers;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\Province;
use Illuminate\Http\Request;

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
