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

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Database\Factories\TemporaryCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property string $value
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?User $user
 */
class TemporaryCode extends BaseModel
{
    use HasFactory;

    public array $protectedFields = [
        'value',
    ];

    protected $table = 'core_org_temporary_codes';

    protected $guarded = ['id'];

    protected $dates = [
        'expires_at',
    ];

    protected $casts = [
        'code' => 'string',
    ];

    public static function newFactory(): TemporaryCodeFactory
    {
        return TemporaryCodeFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expire(): self
    {
        $this->expires_at = now()->subSecond();

        return $this;
    }
}
