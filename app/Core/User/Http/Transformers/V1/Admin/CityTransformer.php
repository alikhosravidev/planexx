<?php

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;

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
