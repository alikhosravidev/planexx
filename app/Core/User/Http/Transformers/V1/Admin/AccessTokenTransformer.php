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

namespace App\Core\User\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;

class AccessTokenTransformer extends BaseTransformer
{
    public function transform($model): array
    {
        return [
            'token'        => $this->resource->token,
            'ip'           => $this->resource->ip,
            'logout_at'    => $this->resource->logout_at,
            'expires_at'   => $this->resource->expires_at,
            'created_at'   => $this->resource->created_at,
            'last_used_at' => $this->resource->last_used_at,
            'is_active'    => null === $this->resource->logout_at
                && (null === $this->resource->expires_at || !$this->resource->expires_at->isPast()),
        ];
    }
}
