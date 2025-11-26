<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Http\Rules\IdentifierCanEmailOrMobileRule;

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
