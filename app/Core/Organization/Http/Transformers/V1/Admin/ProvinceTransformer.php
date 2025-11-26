<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Province;

class ProvinceTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['country'];

    public function includeCountry(Province $province)
    {
        return $this->item($province->country, new CountryTransformer($this->request), 'country');
    }
}
