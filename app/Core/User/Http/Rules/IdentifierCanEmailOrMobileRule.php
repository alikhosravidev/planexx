<?php

declare(strict_types=1);

namespace App\Core\User\Http\Rules;

use App\Core\User\Services\Auth\Exceptions\IdentifierException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use Illuminate\Contracts\Validation\InvokableRule;

final class IdentifierCanEmailOrMobileRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
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
