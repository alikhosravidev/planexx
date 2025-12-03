<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface TaggableEntity
{
    public function tags(): MorphToMany;
}
