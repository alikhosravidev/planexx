<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\Collections\CustomListValueDTOCollection;
use Modules\Product\ValueObjects\CustomListId;

final readonly class CustomListItemDTO implements Arrayable
{
    public function __construct(
        public CustomListId $listId,
        public ?string $referenceCode = null,
        public CustomListValueDTOCollection $values = new CustomListValueDTOCollection(),
    ) {
    }

    public function toArray(): array
    {
        return [
            'list_id'        => $this->listId->value,
            'reference_code' => $this->referenceCode,
        ];
    }
}
