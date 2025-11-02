<?php

declare(strict_types=1);

namespace App\Core\User\Http\Rules;

use App\Core\User\Services\Auth\Exceptions\IdentifierException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class IdentifierCanEmailOrMobileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $identifier = Identifier::fromString($value);
        } catch (IdentifierException $exception) {
            $fail($exception->getMessage());

            return;
        }

        if (! $identifier->type->isMobile() && ! $identifier->type->isEmail()) {
            $fail(trans('user::errors.password_reset_invalid_identifier'));
        }
    }
}
