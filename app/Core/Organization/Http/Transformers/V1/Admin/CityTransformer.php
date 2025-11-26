<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\City;

class CityTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['province'];

    public function includeProvince(City $city)
    {
        return $this->item($city->province, new ProvinceTransformer($this->request), 'province');
    }
}
