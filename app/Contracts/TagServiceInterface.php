<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\Entity\TaggableEntity;
use App\DTOs\TagDTO;
use App\Entities\Tag;
use Illuminate\Support\Collection;

interface TagServiceInterface
{
    public function create(TagDTO $dto): Tag;

    public function update(Tag $tag, TagDTO $dto): Tag;

    public function delete(Tag $tag): bool;

    public function attachToEntity(Tag $tag, TaggableEntity $entity): void;

    public function detachFromEntity(Tag $tag, TaggableEntity $entity): void;

    public function syncTags(TaggableEntity $entity, array $tagIds): void;

    public function getTagsFor(TaggableEntity $entity): Collection;

    public function attachMultiple(TaggableEntity $entity, array $tagIds): void;

    public function detachMultiple(TaggableEntity $entity, array $tagIds): void;

    public function detachAll(TaggableEntity $entity): void;

    public function entityHasTag(TaggableEntity $entity, Tag $tag): bool;

    public function getEntitiesWithTag(Tag $tag, string $entityClass): Collection;
}
