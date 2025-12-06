<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entities\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Favoritable
{
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'entity');
    }
}
