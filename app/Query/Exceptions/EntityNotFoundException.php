<?php

declare(strict_types=1);

namespace App\Query\Exceptions;

use App\Exceptions\TechnicalException;
use App\Query\Contracts\QueryServiceException;

class EntityNotFoundException extends TechnicalException implements QueryServiceException
{
    public static function notFoundId(string $entityName, int|string $id): self
    {
        return new self("Entity {$entityName} with id {$id} not found");
    }
}
