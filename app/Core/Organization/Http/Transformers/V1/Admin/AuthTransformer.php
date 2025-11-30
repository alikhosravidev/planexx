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

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;

class AuthTransformer extends BaseTransformer
{
    protected array $blackListFields = [
        'password',
    ];

    public function transform($model): array
    {
        /** @var \App\Core\Organization\Services\Auth\DTOs\AuthResponse $model */
        if (! empty($model->nextStep)) {
            return [
                'next_step'       => $model->nextStep,
                'identifier'      => $model->identifier?->value,
                'identifier_type' => $model->identifier?->type->value,
                'redirect_url'    => route('web.dashboard'),
            ];
        }

        return [
            'user'            => resolve(UserTransformer::class)->transform($model->user),
            'identifier'      => $model->identifier?->value,
            'identifier_type' => $model->identifier?->type->value,
            'token'           => $model->token?->value,
            'token_type'      => $model->token?->type,
            'success'         => $model->token !== null,
            'is_registered'   => $model->isRegistered,
            'redirect_url'    => route('web.dashboard'),
        ];
    }
}
