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

namespace App\Core\User\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Contracts\User\UserRepositoryInterface;
use App\Core\User\Entities\User;
use App\Core\User\Repositories\Criteria\UserIdentifierCriteria;
use App\Core\User\Services\Auth\ValueObjects\Identifier;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $fieldSearchable = [
        'id'        => '=',
        'slug'      => 'like',
        'full_name' => '=',
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

    public function createFromMobile(string $mobile, ?string $fullName = null): User
    {
        return $this->create(
            [
                'mobile'    => $mobile,
                'full_name' => $fullName,
                'meta'      => [
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
