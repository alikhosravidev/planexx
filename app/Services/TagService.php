<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Entity\TaggableEntity;
use App\Contracts\TagServiceInterface;
use App\DTOs\TagDTO;
use App\Entities\Tag;
use App\Repositories\TagRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

readonly class TagService implements TagServiceInterface
{
    public function __construct(
        private TagRepository $tagRepository,
    ) {
    }

    public function create(TagDTO $dto): Tag
    {
        return $this->tagRepository->create($dto->toArray());
    }

    public function update(Tag $tag, TagDTO $dto): Tag
    {
        return $this->tagRepository->update($tag->id, $dto->toArray());
    }

    public function delete(Tag $tag): bool
    {
        return $this->tagRepository->delete($tag->id);
    }

    public function attachToEntity(Tag $tag, TaggableEntity $entity): void
    {
        $this->validateTaggable($entity);

        if (!$this->entityHasTag($entity, $tag)) {
            $entity->tags()->attach($tag->id);
            $tag->increment('usage_count');
        }
    }

    public function detachFromEntity(Tag $tag, TaggableEntity $entity): void
    {
        $this->validateTaggable($entity);

        if ($this->entityHasTag($entity, $tag)) {
            $entity->tags()->detach($tag->id);
            $this->decrementUsageCount($tag);
        }
    }

    public function syncTags(TaggableEntity $entity, array $tagIds): void
    {
        $this->validateTaggable($entity);

        DB::transaction(function () use ($entity, $tagIds) {
            $currentTagIds = $entity->tags()->pluck('app_tags.id')->all();

            $toDetach = array_diff($currentTagIds, $tagIds);
            $toAttach = array_diff($tagIds, $currentTagIds);

            // کاهش usage_count برای تگ‌های حذف شده
            if (!empty($toDetach)) {
                Tag::whereIn('id', $toDetach)
                    ->where('usage_count', '>', 0)
                    ->decrement('usage_count');
            }

            // افزایش usage_count برای تگ‌های جدید
            if (!empty($toAttach)) {
                Tag::whereIn('id', $toAttach)->increment('usage_count');
            }

            $entity->tags()->sync($tagIds);
        });
    }

    public function getTagsFor(TaggableEntity $entity): Collection
    {
        $this->validateTaggable($entity);

        return $entity->tags()->get();
    }

    public function attachMultiple(TaggableEntity $entity, array $tagIds): void
    {
        $this->validateTaggable($entity);

        DB::transaction(function () use ($entity, $tagIds) {
            $existingTagIds = $entity->tags()->pluck('app_tags.id')->all();
            $newTagIds      = array_diff($tagIds, $existingTagIds);

            if (!empty($newTagIds)) {
                $entity->tags()->attach($newTagIds);
                Tag::whereIn('id', $newTagIds)->increment('usage_count');
            }
        });
    }

    public function detachMultiple(TaggableEntity $entity, array $tagIds): void
    {
        $this->validateTaggable($entity);

        DB::transaction(function () use ($entity, $tagIds) {
            $existingTagIds = $entity->tags()
                ->whereIn('app_tags.id', $tagIds)
                ->pluck('app_tags.id')
                ->all();

            if (!empty($existingTagIds)) {
                $entity->tags()->detach($existingTagIds);
                Tag::whereIn('id', $existingTagIds)
                    ->where('usage_count', '>', 0)
                    ->decrement('usage_count');
            }
        });
    }

    public function detachAll(TaggableEntity $entity): void
    {
        $this->validateTaggable($entity);

        DB::transaction(function () use ($entity) {
            $tagIds = $entity->tags()->pluck('app_tags.id')->all();

            if (!empty($tagIds)) {
                Tag::whereIn('id', $tagIds)
                    ->where('usage_count', '>', 0)
                    ->decrement('usage_count');

                $entity->tags()->detach();
            }
        });
    }

    public function entityHasTag(TaggableEntity $entity, Tag $tag): bool
    {
        return $entity->tags()->where('app_tags.id', $tag->id)->exists();
    }

    public function getEntitiesWithTag(Tag $tag, string $entityClass): Collection
    {
        return $tag->entitiesOfType($entityClass)->get();
    }

    public function incrementUsageCount(Tag $tag): void
    {
        $tag->increment('usage_count');
    }

    public function decrementUsageCount(Tag $tag): void
    {
        if ($tag->usage_count > 0) {
            $tag->decrement('usage_count');
        }
    }

    private function validateTaggable(TaggableEntity $entity): void
    {
        if (!method_exists($entity, 'tags')) {
            throw new InvalidArgumentException(
                sprintf('Entity %s must use Taggable trait', get_class($entity))
            );
        }
    }
}
