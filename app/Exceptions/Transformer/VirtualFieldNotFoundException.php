<?php

declare(strict_types=1);

namespace App\Exceptions\Transformer;

/**
 * Exception thrown when a virtual field resolver is not found.
 */
class VirtualFieldNotFoundException extends TransformerException
{
    public function __construct(string $fieldName)
    {
        parent::__construct("Virtual field '{$fieldName}' resolver not found");
    }
}
