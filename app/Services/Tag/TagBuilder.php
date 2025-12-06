<?php

declare(strict_types=1);

namespace App\Services\Tag;

use App\Contracts\Entity\TaggableEntity;
use App\Entities\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TagBuilder
{
    public function __construct(
        private readonly Tag $tag,
    ) {
    }

    // ═══════════════════════════════════════════════════════════════
    // Attach/Detach Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Attach this tag to an entity
     *
     * @example $tagService->tag($tag)->attachTo($product)
     */
    public function attachTo(TaggableEntity $entity): self
    {
        if (!$this->isAttachedTo($entity)) {
            $entity->tags()->attach($this->tag->id);
            $this->tag->increment('usage_count');
        }

        return $this;
    }

    /**
     * Attach this tag to multiple entities
     *
     * @param  array<TaggableEntity>  $entities
     *
     * @throws \Throwable
     * @example $tagService->tag($tag)->attachToMany([$product1, $product2])
     */
    public function attachToMany(array $entities): self
    {
        DB::transaction(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->attachTo($entity);
            }
        });

        return $this;
    }

    /**
     * Detach this tag from an entity
     *
     * @example $tagService->tag($tag)->detachFrom($product)
     */
    public function detachFrom(TaggableEntity $entity): self
    {
        if ($this->isAttachedTo($entity)) {
            $entity->tags()->detach($this->tag->id);
            $this->decrementUsage();
        }

        return $this;
    }

    /**
     * Detach this tag from multiple entities
     *
     * @param  array<TaggableEntity>  $entities
     *
     * @throws \Throwable
     * @example $tagService->tag($tag)->detachFromMany([$product1, $product2])
     */
    public function detachFromMany(array $entities): self
    {
        DB::transaction(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->detachFrom($entity);
            }
        });

        return $this;
    }

    // ═══════════════════════════════════════════════════════════════
    // Query Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Check if tag is attached to entity
     *
     * @example $tagService->tag($tag)->isAttachedTo($product)
     */
    public function isAttachedTo(TaggableEntity $entity): bool
    {
        return $entity
            ->tags()
            ->where($this->tag->qualifyColumn('id'), $this->tag->id)
            ->exists();
    }

    /**
     * Get all entities of a specific type that have this tag
     *
     * @example $tagService->tag($tag)->entities(Product::class)
     */
    public function entities(string $entityClass): Collection
    {
        return $this->tag->entitiesOfType($entityClass)->get();
    }

    /**
     * Get entities count for a specific type
     *
     * @example $tagService->tag($tag)->entitiesCount(Product::class)
     */
    public function entitiesCount(string $entityClass): int
    {
        return $this->tag->entitiesOfType($entityClass)->count();
    }

    /**
     * Check if tag has any entities of a specific type
     *
     * @example $tagService->tag($tag)->hasEntities(Product::class)
     */
    public function hasEntities(string $entityClass): bool
    {
        return $this->tag->entitiesOfType($entityClass)->exists();
    }

    // ═══════════════════════════════════════════════════════════════
    // Usage Count Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Increment usage count
     *
     * @example $tagService->tag($tag)->incrementUsage()
     */
    public function incrementUsage(int $amount = 1): self
    {
        $this->tag->increment('usage_count', $amount);

        return $this;
    }

    /**
     * Decrement usage count
     *
     * @example $tagService->tag($tag)->decrementUsage()
     */
    public function decrementUsage(int $amount = 1): self
    {
        if ($this->tag->usage_count > 0) {
            $decrement = min($amount, $this->tag->usage_count);
            $this->tag->decrement('usage_count', $decrement);
        }

        return $this;
    }

    /**
     * Reset usage count
     *
     * @example $tagService->tag($tag)->resetUsage()
     */
    public function resetUsage(): self
    {
        $this->tag->update(['usage_count' => 0]);

        return $this;
    }

    /**
     * Get usage count
     *
     * @example $tagService->tag($tag)->usageCount()
     */
    public function usageCount(): int
    {
        return $this->tag->usage_count;
    }

    /**
     * Check if tag is used
     *
     * @example $tagService->tag($tag)->isUsed()
     */
    public function isUsed(): bool
    {
        return $this->tag->usage_count > 0;
    }

    /**
     * Check if tag is unused
     *
     * @example $tagService->tag($tag)->isUnused()
     */
    public function isUnused(): bool
    {
        return $this->tag->usage_count === 0;
    }
}
