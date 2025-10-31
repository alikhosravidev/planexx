<?php

declare(strict_types=1);

namespace App\Core\User\Http\Rules;

use App\Core\User\Services\Auth\Exceptions\IdentifierException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use Illuminate\Contracts\Validation\InvokableRule;

final class IdentifierRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        try {
            Identifier::fromString($value);
        } catch (IdentifierException $exception) {
            $fail($exception->getMessage());
        }
    }
}
