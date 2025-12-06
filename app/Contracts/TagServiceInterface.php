<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\Entity\TaggableEntity;
use App\DTOs\TagDTO;
use App\Entities\Tag;
use App\Services\Tag\TagEntityBuilder;

interface TagServiceInterface
{
    public function create(TagDTO $dto): Tag;

    public function update(Tag $tag, TagDTO $dto): Tag;

    public function delete(Tag $tag): bool;

    public function for(TaggableEntity $entity): TagEntityBuilder;
}
