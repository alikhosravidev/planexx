<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class CustomListValueDTO implements Arrayable
{
    public function __construct(
        public int $attributeId,
        public ?string $valueText = null,
        public ?float $valueNumber = null,
        public ?\DateTimeInterface $valueDate = null,
        public ?bool $valueBoolean = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'attribute_id'  => $this->attributeId,
            'value_text'    => $this->valueText,
            'value_number'  => $this->valueNumber,
            'value_date'    => $this->valueDate?->format('Y-m-d H:i:s'),
            'value_boolean' => $this->valueBoolean,
        ];
    }
}
