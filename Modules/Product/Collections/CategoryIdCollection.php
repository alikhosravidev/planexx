<?php

declare(strict_types=1);

namespace Modules\Product\Collections;

use App\Contracts\BaseCollection;
use Modules\Product\ValueObjects\CategoryId;

class CategoryIdCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = CategoryId::class;
    }

    /**
     * Get array of integer IDs for database operations
     *
     * @return array<int>
     */
    public function toIds(): array
    {
        return array_map(fn (CategoryId $id) => $id->value, $this->all());
    }
}
