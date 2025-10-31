<?php

declare(strict_types=1);

namespace App\Core\User\Http\Requests\API\V1\Client;

use App\Contracts\Requests\BaseRequest;
use App\Core\User\Http\Rules\IdentifierCanEmailOrMobileRule;

class InitiateResetPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                new IdentifierCanEmailOrMobileRule(),
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
