<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\BPMS\Database\Factories\TaskFactory;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                         $id
 * @property string|null                 $slug
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
 * Relations:
 * @property Workflow                     $workflow
 * @property WorkflowState                $currentState
 * @property User                         $assignee
 * @property \Illuminate\Database\Eloquent\Collection<int, FollowUp> $followUps
 * @property \Illuminate\Database\Eloquent\Collection<int, User> $watchers
 */
class Task extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use HasCreator;

    protected $table = 'bpms_tasks';

    protected $fillable = [
        'slug',
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
        return $this->hasMany(FollowUp::class, 'task_id');
    }

    public function watchers(): BelongsToMany
    {
        // through watchlist table
        return $this->belongsToMany(User::class, 'bpms_watchlist', 'task_id', 'watcher_id')
            ->withPivot(['watch_status', 'watch_reason', 'comment'])
            ->withTimestamps();
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
