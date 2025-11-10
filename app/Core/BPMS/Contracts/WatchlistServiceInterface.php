<?php

declare(strict_types=1);

namespace App\Core\BPMS\Contracts;

use App\Core\BPMS\DTOs\WatchlistDTO;
use App\Core\BPMS\Entities\Watchlist;

interface WatchlistServiceInterface
{
    public function upsert(WatchlistDTO $dto): Watchlist;

    public function delete(Watchlist $watchlist): bool;
}
