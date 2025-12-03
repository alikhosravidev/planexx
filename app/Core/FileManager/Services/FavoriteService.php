<?php

declare(strict_types=1);

namespace App\Core\FileManager\Services;

use App\Core\FileManager\Repositories\FavoriteRepository;
use Illuminate\Database\Eloquent\Model;

readonly class FavoriteService
{
    public function __construct(
        private FavoriteRepository $favoriteRepository,
    ) {
    }

    public function toggle(int $userId, Model $entity): bool
    {
        $favorite = $this->favoriteRepository
            ->findWhere([
                'user_id'     => $userId,
                'entity_id'   => $entity->id,
                'entity_type' => $entity->getMorphClass(),
            ])
            ->first();

        if ($favorite) {
            $this->favoriteRepository->delete($favorite->id);

            return false;
        }

        $this->favoriteRepository->create([
            'user_id'     => $userId,
            'entity_id'   => $entity->id,
            'entity_type' => $entity->getMorphClass(),
        ]);

        return true;
    }

    public function isFavorite(int $userId, Model $entity): bool
    {
        return $this->favoriteRepository
            ->findWhere([
                'user_id'     => $userId,
                'entity_id'   => $entity->id,
                'entity_type' => $entity->getMorphClass(),
            ])
            ->isNotEmpty();
    }

    public function remove(int $userId, Model $entity): bool
    {
        $favorite = $this->favoriteRepository
            ->findWhere([
                'user_id'     => $userId,
                'entity_id'   => $entity->id,
                'entity_type' => $entity->getMorphClass(),
            ])
            ->first();

        if (!$favorite) {
            return false;
        }

        return $this->favoriteRepository->delete($favorite->id);
    }
}
