<?php

declare(strict_types=1);

namespace App\Core\User\Http\Rules;

use App\Core\User\Services\Auth\Exceptions\IdentifierException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class IdentifierRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Identifier::fromString($value);
        } catch (IdentifierException $exception) {
            $fail($exception->getMessage());
        }
    }
}
