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

namespace App\Core\User\Http\Transformers\V1\Client;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\User\Http\Transformers\V1\Admin\UserTransformer;

class AuthTransformer extends BaseTransformer
{
    protected array $blackListFields = [
        'password',
    ];

    public function transform($model): null|array
    {
        /** @var \App\Core\User\Services\Auth\DTOs\AuthResponse $model */
        if (! empty($model->nextStep)) {
            return [
                'next_step'       => $model->nextStep,
                'identifier'      => $model->identifier?->value,
                'identifier_type' => $model->identifier?->type->value,
            ];
        }

        return [
            'user'            => (new UserTransformer($this->request))->transformOne($model->user)->toArray(),
            'identifier'      => $model->identifier?->value,
            'identifier_type' => $model->identifier?->type->value,
            'auth'            => $model->token ?? null,
            'is_registered'   => $model->isRegistered,
        ];
    }
}
