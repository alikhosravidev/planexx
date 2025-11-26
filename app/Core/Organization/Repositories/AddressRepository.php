<?php

declare(strict_types = 1);

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
use App\Core\Organization\Entities\Address;

class AddressRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'            => '=',
        'user_id'       => '=',
        'receiver_name' => 'like',
        'postal_code'   => 'like',
        'is_verified'   => '=',
    ];

    public array $sortableFields = [
        'id',
        'receiver_name',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Address::class;
    }
}
