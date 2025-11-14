<?php

declare(strict_types=1);

namespace App\Core\User\Traits;

use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $user_id
 *
 * @property User|null $user
 */
trait HasUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
