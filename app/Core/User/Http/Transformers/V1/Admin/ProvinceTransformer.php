<?php

declare(strict_types=1);

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Entities\Province;

class ProvinceTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['country'];

    public function includeCountry(Province $province)
    {
        return $this->item($province->country, new CountryTransformer($this->request), 'country');
    }
}
