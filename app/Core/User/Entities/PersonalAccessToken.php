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

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModelContract;
use App\Core\User\Database\Factories\PersonalAccessTokenFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as PersonalAccessTokenModel;

/**
 * @property int    $id
 * @property string $tokenable_type
 * @property int    $tokenable_id
 * @property string $name
 * @property string $token
 * @property string $user_agent
 * @property string $fingerprint
 * @property string $ip
 *
 * Casts
 * @property object $abilities
 *
 * Dates
 * @property Carbon $last_used_at
 * @property Carbon $expires_at
 * @property Carbon $logout_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PersonalAccessToken extends PersonalAccessTokenModel implements BaseModelContract
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [];

    public static function findToken($token)
    {
        if (null === $token) {
            return null;
        }

        if (!str_contains($token, '|')) {
            return static::query()
                ->where('token', hash('sha256', $token))
                ->whereNull('logout_at')
                ->first()
            ;
        }

        [$tokenId, $token] = explode('|', $token, 2);

        $instance = static::query()
            ->where('id', '=', $tokenId)
            ->whereNull('logout_at')
            ->first()
        ;

        if ($instance) {
            return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
        }
    }

    protected static function newFactory(): PersonalAccessTokenFactory
    {
        return PersonalAccessTokenFactory::new();
    }
}
