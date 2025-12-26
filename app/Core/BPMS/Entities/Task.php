<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\BPMS\Database\Factories\TaskFactory;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\FileManager\Traits\HasFile;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                         $id
 * @property string                      $title
 * @property string|null                 $description
 * @property int                         $workflow_id
 * @property int                         $current_state_id
 * @property int                         $assignee_id
 * @property TaskPriority                $priority
 * @property string|null                 $estimated_hours
 * @property \Carbon\Carbon|null         $due_date
 * @property \Carbon\Carbon|null         $next_follow_up_date
 * @property \Carbon\Carbon|null         $completed_at
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Accessors
 * @property-read int $progress_percentage
 * @property-read int $current_state_order
 * @property-read int $remaining_days
 *
 * Relations:
 * @property Workflow                     $workflow
 * @property WorkflowState                $currentState
 * @property User                         $assignee
 * @property \Illuminate\Database\Eloquent\Collection<int, FollowUp> $followUps
 * @property \Illuminate\Database\Eloquent\Collection<int, User> $watchers
 */
class Task extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;
    use HasCreator;
    use HasFile;

    public const TABLE = 'bpms_tasks';
    protected $table   = self::TABLE;

    protected $fillable = [
        'title',
        'description',
        'workflow_id',
        'current_state_id',
        'assignee_id',
        'created_by',
        'priority',
        'estimated_hours',
        'due_date',
        'next_follow_up_date',
        'completed_at',
    ];

    protected $casts = [
        'priority'            => TaskPriority::class,
        'due_date'            => 'datetime',
        'next_follow_up_date' => 'datetime',
        'completed_at'        => 'datetime',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function currentState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class, 'current_state_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'task_id')
            ->orderBy('created_at', 'desc');
    }

    public function watchers(): BelongsToMany
    {
        // through watchlist table
        return $this->belongsToMany(User::class, 'bpms_watchlist', 'task_id', 'watcher_id')
            ->withPivot(['watch_status', 'watch_reason', 'comment'])
            ->withTimestamps();
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->relationLoaded('workflow') || !$this->workflow->relationLoaded('states')) {
                    return 0;
                }
                $totalStates  = $this->workflow->states->count();
                $currentOrder = $this->current_state_order;

                return $totalStates > 0 ? (int) round(($currentOrder / $totalStates) * 100) : 0;
            }
        );
    }

    protected function currentStateOrder(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->currentState?->order ?? 1;
            }
        );
    }

    protected function remainingDays(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->due_date) {
                    return null;
                }
                $now = Carbon::now()->startOfDay();

                return (int) $now->diffInDays($this->due_date, false);
            }
        );
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
