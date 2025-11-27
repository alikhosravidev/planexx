<?php

declare(strict_types=1);

namespace App\Exceptions;

class EntityNotFoundException extends TechnicalException
{
    public static function notFoundId(string $entityName, int|string $id): self
    {
        return new self("Entity {$entityName} with id {$id} not found");
    }
}
