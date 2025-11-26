<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Http\Rules\IdentifierRule;

class AuthInitiateRequest extends BaseRequest
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
        ];
    }

    public function attributes(): array
    {
        return [
            'identifier' => 'نام کاربری، ایمیل یا شماره تماس',
        ];
    }
}
