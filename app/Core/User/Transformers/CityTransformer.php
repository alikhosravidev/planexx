<?php

namespace App\Core\User\Transformers;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\City;
use Illuminate\Http\Request;

class CityTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['province'];

    public function includeProvince($city)
    {
        if ($city->province) {
            return $this->item($city->province, new ProvinceTransformer($this->request), 'province');
        }
    }
}
