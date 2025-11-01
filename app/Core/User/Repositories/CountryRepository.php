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

namespace App\Core\User\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\User\Entities\Country;

class CountryRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Country::class;
    }
}
