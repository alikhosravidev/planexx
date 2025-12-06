<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface FavoritableEntity
{
    public function favorites(): MorphMany;
}
