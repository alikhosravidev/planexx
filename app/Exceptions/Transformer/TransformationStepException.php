<?php

declare(strict_types=1);

namespace App\Exceptions\Transformer;

/**
 * Exception thrown when a transformation step fails.
 */
class TransformationStepException extends TransformerException
{
    public function __construct(string $stepClass, string $message = '', ?\Throwable $previous = null)
    {
        $fullMessage = "Transformation step '{$stepClass}' failed";

        if ($message) {
            $fullMessage .= ": {$message}";
        }

        parent::__construct($fullMessage, 0, $previous);
    }
}
