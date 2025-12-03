<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entities\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'entity', 'app_entity_has_tags')
            ->withTimestamps();
    }
}
