<?php

declare(strict_types=1);

namespace Modules\Product\Collections;

use App\Contracts\BaseCollection;
use Modules\Product\DTOs\CustomListAttributeDTO;

class CustomListAttributeDTOCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = CustomListAttributeDTO::class;
    }
}
