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
use App\Services\Transformer\FieldTransformers\DateTimeTransformer;

class UserTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'email_verified_at'  => DateTimeTransformer::class,
        'mobile_verified_at' => DateTimeTransformer::class,
        'last_login_at'      => DateTimeTransformer::class,
        'employment_date'    => DateTimeTransformer::class,
    ];
}
