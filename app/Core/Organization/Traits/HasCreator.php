<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $created_by
 *
 * @property User|null $creator
 */
trait HasCreator
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
