<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\WatchlistServiceInterface;
use App\Core\BPMS\DTOs\WatchlistDTO;
use App\Core\BPMS\Entities\Watchlist;
use App\Core\BPMS\Repositories\WatchlistRepository;

readonly class WatchlistService implements WatchlistServiceInterface
{
    public function __construct(
        private WatchlistRepository $watchlistRepository,
    ) {
    }

    public function upsert(WatchlistDTO $dto): Watchlist
    {
        $existing = $this->watchlistRepository
            ->makeModel()
            ->where('task_id', $dto->taskId->value)
            ->where('watcher_id', $dto->watcherId->value)
            ->first();

        if ($existing) {
            return $this->watchlistRepository->update($existing->id, $dto->toArray());
        }

        return $this->watchlistRepository->create($dto->toArray());
    }

    public function delete(Watchlist $watchlist): bool
    {
        return $this->watchlistRepository->delete($watchlist->id);
    }
}
