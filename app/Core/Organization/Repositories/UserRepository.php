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

namespace App\Core\Organization\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Repositories\Criteria\UserIdentifierCriteria;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

class UserRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'        => '=',
        'full_name' => 'like',
        'mobile'    => '=',
        'email'     => '=',
    ];

    public array $filterableFields = [
        'user_type'      => '=',
        'is_active'      => '=',
        'departments.id' => '=',
    ];

    public array $sortableFields = [
        'id',
        'full_name',
        'mobile',
        'email',
        'created_at',
        'updated_at',
    ];

    /**
     * Custom Filters Configuration.
     *
     * Available filters:
     * - has_roles: Filters users who have at least one of the specified roles
     *   Parameters: [array $roles] - Array of role names or IDs
     *
     * @var array<string, class-string>
     */
    public array $customFilters = [
        'has_roles' => Criteria\UserHasRolesCriteria::class,
    ];

    public function model(): string
    {
        return User::class;
    }

    public function findByMobile(string $mobile): ?User
    {
        return $this->newQuery()
            ->where(function ($query) use ($mobile) {
                $query->where('mobile', $mobile)
                    ->orWhere('mobile2', $mobile);
            })
            ->first();
    }

    public function createFromMobile(string $mobile, string $fullName = null): User
    {
        $nameParts = explode(' ', $fullName);

        return $this->create(
            [
                'mobile'     => $mobile,
                'full_name'  => $fullName,
                'first_name' => $nameParts[0],
                'last_name'  => $nameParts[1] ?? '',
                'meta'       => [
                    'mobile_verified' => true,
                ],
            ]
        );
    }

    public function findWithIdentifier(Identifier $identifier): ?User
    {
        return $this
            ->pushCriteria(new UserIdentifierCriteria($identifier))
            ->first();
    }

    // Tell, Don't Ask
    public function registerWithIdentifier(Identifier $identifier, ?string $password = null): User
    {
        $keyType = $identifier->type->value;

        return $this->create(
            [
                $keyType   => $identifier->value,
                'password' => $password,
            ]
        );
    }
}
