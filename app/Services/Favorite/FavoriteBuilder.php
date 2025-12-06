<?php

declare(strict_types=1);

namespace App\Services\Favorite;

use App\Contracts\Entity\FavoritableEntity;
use App\Entities\Favorite;
use App\Repositories\FavoriteRepository;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FavoriteBuilder
{
    private ?FavoritableEntity $entity = null;
    private ?int $userId               = null;

    public function __construct(
        private readonly FavoriteRepository $repository,
        ?FavoritableEntity $entity = null,
    ) {
        $this->entity = $entity;
    }

    /**
     * Set the target entity
     *
     * @example ->for($product)->by($userId)->add()
     */
    public function for(FavoritableEntity $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Set the user
     *
     * @example ->by($userId)->toggle()
     */
    public function by(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Alias for by()
     */
    public function user(int $userId): self
    {
        return $this->by($userId);
    }

    /**
     * Add entity to favorites
     *
     * @example $service->for($product)->by($userId)->add()
     */
    public function add(): Favorite
    {
        $this->ensureEntityAndUserAreSet();

        if ($this->exists()) {
            return $this->first();
        }

        return $this->repository->create(
            [
                'user_id'     => $this->userId,
                'entity_id'   => $this->entity->id,
                'entity_type' => $this->entity->getMorphClass(),
            ]
        );
    }

    /**
     * Remove entity from favorites
     *
     * @example $service->for($product)->by($userId)->remove()
     */
    public function remove(): bool
    {
        $this->ensureEntityAndUserAreSet();

        return (bool) $this->entity
            ->favorites()
            ->where('user_id', $this->userId)
            ->delete();
    }

    /**
     * Toggle favorite status
     *
     * @example $service->for($product)->by($userId)->toggle()
     * @return bool Returns true if added, false if removed
     */
    public function toggle(): bool
    {
        $this->ensureEntityAndUserAreSet();

        if ($this->exists()) {
            $this->remove();

            return false;
        }

        $this->add();

        return true;
    }

    /**
     * Check if entity is favorited
     *
     * @example $service->for($product)->by($userId)->exists()
     */
    public function exists(): bool
    {
        $this->ensureEntityAndUserAreSet();

        return $this->entity
            ->favorites()
            ->where('user_id', $this->userId)
            ->exists();
    }

    /**
     * Alias for exists()
     */
    public function isFavorited(): bool
    {
        return $this->exists();
    }

    /**
     * Get first favorite record
     */
    public function first(): ?Favorite
    {
        $this->ensureEntityIsSet();

        $query = $this->entity->favorites();

        if ($this->userId !== null) {
            $query->where('user_id', $this->userId);
        }

        return $query->first();
    }

    /**
     * Get all favorites
     *
     * @example $service->for($product)->get()           // All favorites for product
     * @example $service->for($product)->by($userId)->get() // User's favorites for product
     */
    public function get(): Collection
    {
        $this->ensureEntityIsSet();

        $query = $this->entity->favorites();

        if ($this->userId !== null) {
            $query->where('user_id', $this->userId);
        }

        return $query->get();
    }

    /**
     * Get favorites count
     *
     * @example $service->for($product)->count()
     */
    public function count(): int
    {
        $this->ensureEntityIsSet();

        $query = $this->entity->favorites();

        if ($this->userId !== null) {
            $query->where('user_id', $this->userId);
        }

        return $query->count();
    }

    /**
     * Get all user IDs who favorited this entity
     *
     * @example $service->for($product)->userIds()
     */
    public function userIds(): array
    {
        $this->ensureEntityIsSet();

        return $this->entity
            ->favorites()
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * Check if entity has any favorites
     *
     * @example $service->for($product)->hasFavorites()
     */
    public function hasFavorites(): bool
    {
        $this->ensureEntityIsSet();

        return $this->entity->favorites()->exists();
    }

    /**
     * Remove all favorites for entity
     *
     * @example $service->for($product)->clear()
     */
    public function clear(): int
    {
        $this->ensureEntityIsSet();

        return $this->entity->favorites()->delete();
    }

    // ─────────────────────────────────────────────────────────────
    // Validation Methods
    // ─────────────────────────────────────────────────────────────

    private function ensureEntityIsSet(): void
    {
        if ($this->entity === null) {
            throw new InvalidArgumentException(
                'Entity must be set. Use for() method first.'
            );
        }
    }

    private function ensureUserIsSet(): void
    {
        if ($this->userId === null) {
            throw new InvalidArgumentException(
                'User must be set. Use by() or user() method first.'
            );
        }
    }

    private function ensureEntityAndUserAreSet(): void
    {
        $this->ensureEntityIsSet();
        $this->ensureUserIsSet();
    }
}
