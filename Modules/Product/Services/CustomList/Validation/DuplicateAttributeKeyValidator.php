<?php

declare(strict_types=1);

namespace Modules\Product\Services\CustomList\Validation;

use Modules\Product\Collections\CustomListAttributeDTOCollection;
use Modules\Product\DTOs\CustomListAttributeDTO;
use Modules\Product\Exceptions\CustomListException;

/**
 * Validates that no two attributes in a collection share
 * the same key_name.
 */
final class DuplicateAttributeKeyValidator
{
    /**
     * @throws CustomListException
     */
    public function validate(CustomListAttributeDTOCollection $attributes): void
    {
        if ($attributes->isEmpty()) {
            return;
        }

        $keyNames   = $attributes->map(fn (CustomListAttributeDTO $a) => $a->keyName)->toArray();
        $counts     = array_count_values($keyNames);
        $duplicates = array_keys(array_filter($counts, fn (int $count) => $count > 1));

        if (! empty($duplicates)) {
            throw CustomListException::duplicateAttributeKeys($duplicates);
        }
    }
}
