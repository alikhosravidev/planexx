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
use App\Core\Organization\Entities\TemporaryCode;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use Carbon\Carbon;

class TemporaryCodeRepository extends BaseRepository
{
    public function model(): string
    {
        return TemporaryCode::class;
    }

    public function findActiveCode(Identifier $identifier, string $code): ?TemporaryCode
    {
        return $this
            ->newQuery()
            //->where('channel', '=', $identifier->type->getChannel())
            ->where('value', '=', $identifier->value)
            ->where('code', '=', $code)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function findForIdentifier(Identifier $identifier): ?TemporaryCode
    {
        return $this
            ->newQuery()
            //->where('channel', '=', $identifier->type->getChannel())
            ->where('value', '=', $identifier->value)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function updateOrCreateFor(
        Identifier $identifier,
        string     $newCode,
        Carbon     $expiresAt,
        ?int       $userId = null,
    ): TemporaryCode {
        return $this->newQuery()
            ->updateOrCreate(
                [
                    //'channel' => $identifier->type->getChannel(),
                    'value'   => $identifier->value,
                    'user_id' => $userId,
                ],
                [
                    'code'       => $newCode,
                    'expires_at' => $expiresAt,
                ]
            );
    }
}
