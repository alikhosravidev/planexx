<?php

declare(strict_types=1);

namespace App\Exceptions\Transformer;

/**
 * Exception thrown when an invalid transformer is encountered.
 */
class InvalidTransformerException extends TransformerException
{
    public function __construct(string $message = 'Invalid transformer provided', array $errors = [])
    {
        parent::__construct($message, $errors);
    }
}
