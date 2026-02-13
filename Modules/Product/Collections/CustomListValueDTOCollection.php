<?php

declare(strict_types=1);

namespace Modules\Product\Collections;

use App\Contracts\BaseCollection;
use Modules\Product\DTOs\CustomListValueDTO;

class CustomListValueDTOCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = CustomListValueDTO::class;
    }
}
