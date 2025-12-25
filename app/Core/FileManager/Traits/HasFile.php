<?php

declare(strict_types=1);

namespace App\Core\FileManager\Traits;

use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Enums\FileCollectionEnum;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property File|null $image
 * @property File|null $avatar
 * @property File|null $thumbnail
 */
trait HasFile
{
    public function image(): MorphOne
    {
        return $this->morphOne(File::class, 'entity')
            ->where('mime_type', 'LIKE', 'image%');
    }

    public function thumbnail(): MorphOne
    {
        return $this->morphOne(File::class, 'entity')
            ->where('mime_type', 'LIKE', 'image%')
            ->where('collection', '=', FileCollectionEnum::THUMBNAIL);
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(File::class, 'entity')
            ->where('mime_type', 'LIKE', 'image%')
            ->where('collection', '=', FileCollectionEnum::AVATAR);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(File::class, 'entity')
            ->where('collection', '=', FileCollectionEnum::ATTACHMENT);
    }
}
