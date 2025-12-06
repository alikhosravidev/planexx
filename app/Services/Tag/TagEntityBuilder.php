<?php

declare(strict_types=1);

namespace App\Services\Tag;

use App\Contracts\Entity\TaggableEntity;
use App\Entities\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TagEntityBuilder
{
    private string $tagTable;

    public function __construct(
        private readonly TaggableEntity $entity,
    ) {
        $this->tagTable = Tag::TABLE;
    }

    /**
     * Attach tag(s) to entity
     *
     * @param  Tag|int|array<Tag|int>  $tags
     *
     * @throws \Throwable
     * @example ->attach(5)
     * @example ->attach([$tag1, $tag2])
     * @example ->attach([1, 2, 3])
     * @example ->attach($tag)
     */
    public function attach(Tag|int|array $tags): self
    {
        $tagIds = $this->normalizeTagIds($tags);

        if (empty($tagIds)) {
            return $this;
        }

        DB::transaction(function () use ($tagIds) {
            $existingIds = $this->getExistingTagIds();
            $newIds      = array_diff($tagIds, $existingIds);

            if (!empty($newIds)) {
                $this->entity->tags()->attach($newIds);
                Tag::whereIn('id', $newIds)->increment('usage_count');
            }
        });

        return $this;
    }

    // ═══════════════════════════════════════════════════════════════
    // Detach Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Detach tag(s) from entity
     *
     * @param Tag|int|array<Tag|int> $tags
     *
     * @example ->detach($tag)
     * @example ->detach([1, 2, 3])
     */
    public function detach(Tag|int|array $tags): self
    {
        $tagIds = $this->normalizeTagIds($tags);

        if (empty($tagIds)) {
            return $this;
        }

        DB::transaction(function () use ($tagIds) {
            $existingIds = $this->entity
                ->tags()
                ->whereIn("{$this->tagTable}.id", $tagIds)
                ->pluck("{$this->tagTable}.id")
                ->all();

            if (!empty($existingIds)) {
                $this->entity->tags()->detach($existingIds);
                $this->decrementUsageCounts($existingIds);
            }
        });

        return $this;
    }

    /**
     * Detach all tags from entity
     *
     * @example ->clear()
     */
    public function clear(): self
    {
        DB::transaction(function () {
            $tagIds = $this->getExistingTagIds();

            if (!empty($tagIds)) {
                $this->decrementUsageCounts($tagIds);
                $this->entity->tags()->detach();
            }
        });

        return $this;
    }

    // ═══════════════════════════════════════════════════════════════
    // Sync Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Sync tags for entity
     *
     * @param array<Tag|int> $tags
     *
     * @example ->sync([1, 2, 3])
     * @example ->sync([$tag1, $tag2])
     */
    public function sync(array $tags): self
    {
        $tagIds = $this->normalizeTagIds($tags);

        DB::transaction(function () use ($tagIds) {
            $currentIds = $this->getExistingTagIds();

            $toDetach = array_diff($currentIds, $tagIds);
            $toAttach = array_diff($tagIds, $currentIds);

            if (!empty($toDetach)) {
                $this->decrementUsageCounts($toDetach);
            }

            if (!empty($toAttach)) {
                Tag::whereIn('id', $toAttach)->increment('usage_count');
            }

            $this->entity->tags()->sync($tagIds);
        });

        return $this;
    }

    /**
     * Replace all tags (alias for sync)
     *
     * @example ->replaceWith([1, 2, 3])
     */
    public function replaceWith(array $tags): self
    {
        return $this->sync($tags);
    }

    // ═══════════════════════════════════════════════════════════════
    // Query Operations
    // ═══════════════════════════════════════════════════════════════

    /**
     * Check if entity has specific tag
     *
     * @example ->has($tag)
     * @example ->has(5)
     */
    public function has(Tag|int $tag): bool
    {
        $tagId = $tag instanceof Tag ? $tag->id : $tag;

        return $this->entity
            ->tags()
            ->where("{$this->tagTable}.id", $tagId)
            ->exists();
    }

    /**
     * Check if entity has any of the given tags
     *
     * @example ->hasAny([1, 2, 3])
     */
    public function hasAny(array $tags): bool
    {
        $tagIds = $this->normalizeTagIds($tags);

        return $this->entity
            ->tags()
            ->whereIn("{$this->tagTable}.id", $tagIds)
            ->exists();
    }

    /**
     * Check if entity has all of the given tags
     *
     * @example ->hasAll([1, 2, 3])
     */
    public function hasAll(array $tags): bool
    {
        $tagIds = $this->normalizeTagIds($tags);

        return $this->entity
                ->tags()
                ->whereIn("{$this->tagTable}.id", $tagIds)
                ->count() === count($tagIds);
    }

    /**
     * Check if entity has any tags
     *
     * @example ->isTagged()
     */
    public function isTagged(): bool
    {
        return $this->entity->tags()->exists();
    }

    /**
     * Check if entity has no tags
     *
     * @example ->isEmpty()
     */
    public function isEmpty(): bool
    {
        return !$this->isTagged();
    }

    /**
     * Get all tags for entity
     *
     * @example ->get()
     */
    public function get(): Collection
    {
        return $this->entity->tags()->get();
    }

    /**
     * Get tag IDs
     *
     * @example ->tagIds()
     */
    public function tagIds(): array
    {
        return $this->getExistingTagIds();
    }

    /**
     * Get tags count
     *
     * @example ->count()
     */
    public function count(): int
    {
        return $this->entity->tags()->count();
    }

    /**
     * Get first tag
     *
     * @example ->first()
     */
    public function first(): ?Tag
    {
        return $this->entity->tags()->first();
    }

    // ═══════════════════════════════════════════════════════════════
    // Private Helpers
    // ═══════════════════════════════════════════════════════════════

    /**
     * @param Tag|int|array<Tag|int> $tags
     * @return array<int>
     */
    private function normalizeTagIds(Tag|int|array $tags): array
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        return array_map(
            fn ($tag) => $tag instanceof Tag ? $tag->id : $tag,
            $tags
        );
    }

    private function getExistingTagIds(): array
    {
        return $this->entity
            ->tags()
            ->pluck("{$this->tagTable}.id")
            ->all();
    }

    private function decrementUsageCounts(array $tagIds): void
    {
        Tag::whereIn('id', $tagIds)
            ->where('usage_count', '>', 0)
            ->decrement('usage_count');
    }
}
