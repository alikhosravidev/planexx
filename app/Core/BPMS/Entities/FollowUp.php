<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\BPMS\Database\Factories\FollowUpFactory;
use App\Core\BPMS\Enums\FollowUpType;
use App\Core\User\Entities\User;
use App\Core\User\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                  $id
 * @property int                  $task_id
 * @property FollowUpType         $type
 * @property string|null          $content
 * @property int                  $created_by
 * @property int|null             $previous_assignee_id
 * @property int|null             $new_assignee_id
 * @property int|null             $previous_state_id
 * @property int|null             $new_state_id
 * @property \Carbon\Carbon      $created_at
 *
 * Relations:
 * @property Task                 $task
 * @property User|null            $previousAssignee
 * @property User|null            $newAssignee
 * @property WorkflowState|null   $previousState
 * @property WorkflowState|null   $newState
 */
class FollowUp extends BaseModel
{
    use HasFactory;
    use HasCreator;

    public $timestamps = false;

    protected $table = 'bpms_follow_ups';

    protected $fillable = [
        'task_id',
        'type',
        'content',
        'created_by',
        'previous_assignee_id',
        'new_assignee_id',
        'previous_state_id',
        'new_state_id',
        'created_at',
    ];

    protected $casts = [
        'type'       => FollowUpType::class,
        'created_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function previousAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'previous_assignee_id');
    }

    public function newAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_assignee_id');
    }

    public function previousState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class, 'previous_state_id');
    }

    public function newState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class, 'new_state_id');
    }

    protected static function newFactory(): FollowUpFactory
    {
        return FollowUpFactory::new();
    }
}
