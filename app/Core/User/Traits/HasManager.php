<?php

declare(strict_types=1);

namespace App\Core\User\Traits;

use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $manager_id
 *
 * @property User|null $manager
 */
trait HasManager
{
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
