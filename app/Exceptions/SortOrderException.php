<?php

declare(strict_types=1);

namespace App\Exceptions;

class SortOrderException extends TechnicalException
{
    public static function currentOrderNotAvailable(int $id): self
    {
        return new self("Current sort order not available for model id {$id}.");
    }
}
