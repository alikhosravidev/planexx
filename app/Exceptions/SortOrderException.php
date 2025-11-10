<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class SortOrderException extends Exception
{
    public static function currentOrderNotAvailable(int $id): self
    {
        return new self("Current sort order not available for model id {$id}.");
    }
}
