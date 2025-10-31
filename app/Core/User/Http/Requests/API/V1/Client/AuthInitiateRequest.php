<?php

declare(strict_types=1);

namespace App\Core\User\Http\Requests\API\V1\Client;

use App\Contracts\Requests\BaseRequest;
use App\Core\User\Http\Rules\IdentifierRule;

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
