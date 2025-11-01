<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\User\Http\Requests\V1\Client;

use App\Contracts\Requests\BaseRequest;
use App\Core\User\Http\Rules\IdentifierRule;
use App\Core\User\Http\Rules\PasswordDifficultyRule;

class ResetPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                new IdentifierRule(),
            ],
            'code' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                // The field under validation must match the authenticated user's password.
                // You may specify an authentication guard using the rule's first parameter
                resolve(PasswordDifficultyRule::class),
            ],
            'repeat_password' => [
                'required',
                // The field under validation must have a matching field of {field}_confirmation.
                // For example, if the field under validation is password,
                // a matching password_confirmation field must be present in the input.
                'same:password',
            ],
        ];
    }

    public function attributes(): array
    {
        return trans('user::attributes.auth');
    }
}
