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

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Http\Rules\IdentifierRule;

class AuthRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                new IdentifierRule(),
            ],
            'authType' => [
                'sometimes',
                'in:otp',
            ],
            'password' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'identifier' => 'نام کاربری، ایمیل یا شماره تماس',
            'password'   => 'رمز عبور یا کدیکبار مصرف',
        ];
    }
}
