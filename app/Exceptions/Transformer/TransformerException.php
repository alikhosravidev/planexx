<?php

declare(strict_types=1);

namespace App\Exceptions\Transformer;

use App\Exceptions\TechnicalException;

/**
 * Base exception for transformer-related errors.
 */
class TransformerException extends TechnicalException
{
    public function __construct(string $message = 'Transformer error', array $errors = [])
    {
        parent::__construct($message, $errors);
    }
}
