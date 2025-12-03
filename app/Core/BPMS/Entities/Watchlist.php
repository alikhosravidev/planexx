<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\BPMS\Database\Factories\WatchlistFactory;
use App\Core\BPMS\Enums\WatchStatus;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                 $id
 * @property int                 $task_id
 * @property int                 $watcher_id
 * @property WatchStatus         $watch_status
 * @property string|null         $watch_reason
 * @property string|null         $comment
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 *
 * Relations:
 * @property Task                 $task
 * @property User                 $watcher
 */
class Watchlist extends BaseEntity
{
    use HasFactory;

    protected $table = 'bpms_watchlist';

    protected $fillable = [
        'task_id',
        'watcher_id',
        'watch_status',
        'watch_reason',
        'comment',
    ];

    protected $casts = [
        'watch_status' => WatchStatus::class,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function watcher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'watcher_id');
    }

    protected static function newFactory(): WatchlistFactory
    {
        return WatchlistFactory::new();
    }
}
