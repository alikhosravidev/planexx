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

    protected array $availableIncludes = ['directManager', 'jobPosition', 'departments', 'avatar', 'roles'];

    public function includeDirectManager(User $user)
    {
        return $this->itemRelation(
            model: $user,
            relationName: 'directManager',
            transformer: $this,
            foreignKey: 'direct_manager_id',
        );
    }

    public function includeAvatar(User $user)
    {
        return $this->itemRelation(
            model: $user,
            relationName: 'avatar',
            transformer: $this,
        );
    }

    public function includeJobPosition(User $user)
    {
        return $this->itemRelation(
            model: $user,
            relationName: 'jobPosition',
            transformer: JobPositionTransformer::class,
            foreignKey: 'job_position_id',
        );
    }

    public function includeDepartments(User $user)
    {
        return $this->collectionRelation(
            model: $user,
            relationName: 'departments',
            transformer: DepartmentTransformer::class,
        );
    }

    public function includeRoles(User $user)
    {
        return $this->collectionRelation(
            model: $user,
            relationName: 'roles',
            transformer: RoleTransformer::class,
        );
    }
}
