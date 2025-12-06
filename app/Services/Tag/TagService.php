<?php

declare(strict_types=1);

namespace App\Services\Tag;

use App\Contracts\Entity\TaggableEntity;
use App\Contracts\TagServiceInterface;
use App\DTOs\TagDTO;
use App\Entities\Tag;
use App\Repositories\TagRepository;

readonly class TagService implements TagServiceInterface
{
    public function __construct(
        private TagRepository $tagRepository,
    ) {
    }

    /**
     * Start building operations for an entity
     *
     * @example $tagService->for($product)->attach($tag)
     * @example $tagService->for($product)->sync([1, 2, 3])
     */
    public function for(TaggableEntity $entity): TagEntityBuilder
    {
        return new TagEntityBuilder($entity);
    }

    /**
     * Start building operations for a tag
     *
     * @example $tagService->tag($tag)->attachTo($product)
     * @example $tagService->tag($tag)->entities(Product::class)
     */
    public function tag(Tag $tag): TagBuilder
    {
        return new TagBuilder($tag);
    }

    // ═══════════════════════════════════════════════════════════════
    // CRUD Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Create a new tag
     */
    public function create(TagDTO $dto): Tag
    {
        return $this->tagRepository->create($dto->toArray());
    }

    /**
     * Update an existing tag
     */
    public function update(Tag $tag, TagDTO $dto): Tag
    {
        return $this->tagRepository->update($tag->id, $dto->toArray());
    }

    /**
     * Delete a tag
     */
    public function delete(Tag $tag): bool
    {
        return $this->tagRepository->delete($tag->id);
    }
}
