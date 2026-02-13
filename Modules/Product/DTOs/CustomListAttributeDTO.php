<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\Enums\AttributeDataTypeEnum;

final readonly class CustomListAttributeDTO implements Arrayable
{
    public function __construct(
        public string $label,
        public string $keyName,
        public AttributeDataTypeEnum $dataType = AttributeDataTypeEnum::Text,
        public bool $isRequired = false,
        public int $sortOrder = 0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'label'       => $this->label,
            'key_name'    => $this->keyName,
            'data_type'   => $this->dataType->value,
            'is_required' => $this->isRequired,
            'sort_order'  => $this->sortOrder,
        ];
    }
}
