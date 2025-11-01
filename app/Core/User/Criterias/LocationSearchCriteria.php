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

namespace App\Core\User\Criterias;

use App\Contracts\Repository\BaseCriteria;
use Illuminate\Database\Eloquent\Builder;

class LocationSearchCriteria extends BaseCriteria
{
    public function apply(Builder $query): Builder
    {
        $searchKey = $this->parameters['search_key'] ?? null;

        if (null === $searchKey) {
            return $query;
        }

        return $query
            ->where(function (Builder $query) use ($searchKey): void {
                $query
                    ->orWhere('id', '=', $searchKey)
                    ->orWhere('name', 'LIKE', "%{$searchKey}%")
                    ->orWhere('name_en', 'LIKE', "%{$searchKey}%")
                ;
            })
        ;
    }
}
