<?php

declare(strict_types=1);

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\City;

class CityTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['province'];

    public function includeProvince(City $city)
    {
        return $this->item($city->province, new ProvinceTransformer($this->request), 'province');
    }
}
