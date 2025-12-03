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
use App\Core\Organization\Entities\User;
use App\Services\Transformer\FieldTransformers\DateTimeTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;
use App\Services\Transformer\FieldTransformers\ValueObjectTransformer;

class UserTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'mobile'             => ValueObjectTransformer::class,
        'email'              => ValueObjectTransformer::class,
        'user_type'          => EnumTransformer::class,
        'customer_type'      => EnumTransformer::class,
        'gender'             => EnumTransformer::class,
        'email_verified_at'  => DateTimeTransformer::class,
        'mobile_verified_at' => DateTimeTransformer::class,
        'last_login_at'      => DateTimeTransformer::class,
        'employment_date'    => DateTimeTransformer::class,
        'birth_date'         => DateTimeTransformer::class,
    ];

    protected array $availableIncludes = ['directManager', 'jobPosition', 'departments'];

    public function includeDirectManager(User $user)
    {
        if (!$user->direct_manager_id || !$user->relationLoaded('directManager')) {
            return null;
        }

        return $this->item($user->directManager, resolve(self::class));
    }

    public function includeJobPosition(User $user)
    {
        if (!$user->job_position_id || !$user->relationLoaded('jobPosition')) {
            return null;
        }

        return $this->item($user->jobPosition, resolve(JobPositionTransformer::class));
    }

    public function includeDepartments(User $user)
    {
        if (!$user->relationLoaded('departments')) {
            return null;
        }

        return $this->collection($user->departments, resolve(DepartmentTransformer::class));
    }
}
