<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Watchlist;

class WatchlistRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'           => '=',
        'task_id'      => '=',
        'watcher_id'   => '=',
        'watch_status' => '=',
    ];

    public array $sortableFields = [
        'id', 'created_at', 'updated_at',
    ];

    public function model(): string
    {
        return Watchlist::class;
    }
}
