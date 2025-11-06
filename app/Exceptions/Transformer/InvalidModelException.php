<?php

declare(strict_types=1);

namespace App\Exceptions\Transformer;

/**
 * Exception thrown when an invalid model is provided.
 */
class InvalidModelException extends TransformerException
{
    public function __construct(string $message = 'Invalid model provided', array $errors = [])
    {
        parent::__construct($message, $errors);
    }
}
