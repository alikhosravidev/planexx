<?php

declare(strict_types=1);

namespace App\Services\Favorite;

use App\Contracts\Entity\FavoritableEntity;
use App\Repositories\FavoriteRepository;

readonly class FavoriteService
{
    public function __construct(
        private FavoriteRepository $favoriteRepository,
    ) {
    }

    /**
     * Start building a favorite query for an entity
     *
     * @example $favoriteService->for($product)->by($userId)->add()
     */
    public function for(FavoritableEntity $entity): FavoriteBuilder
    {
        return new FavoriteBuilder($this->favoriteRepository, $entity);
    }

    /**
     * Start building a favorite query for a user
     *
     * @example $favoriteService->user($userId)->for($product)->toggle()
     */
    public function user(int $userId): FavoriteBuilder
    {
        return (new FavoriteBuilder($this->favoriteRepository))->by($userId);
    }
}
