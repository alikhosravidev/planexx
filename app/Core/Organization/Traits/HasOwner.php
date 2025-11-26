<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $owner_id
 *
 * @property User|null $owner
 */
trait HasOwner
{
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
