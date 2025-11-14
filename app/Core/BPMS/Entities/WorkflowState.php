<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Model\BaseModel;
use App\Contracts\Sorting\SortableEntity;
use App\Core\BPMS\Database\Factories\WorkflowStateFactory;
use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Core\User\Entities\User;
use App\Traits\Sorting\HasSorting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                         $id
 * @property int                         $workflow_id
 * @property string                      $name
 * @property string|null                 $slug
 * @property string|null                 $description
 * @property string|null                 $color
 * @property int                         $order
 * @property WorkflowStatePosition       $position
 * @property int|null                    $default_assignee_id
 * @property bool                        $is_active
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property Workflow                     $workflow
 * @property User|null                    $defaultAssignee
 * @property \Illuminate\Database\Eloquent\Collection<int, Task> $tasks
 */
class WorkflowState extends BaseModel implements SortableEntity
{
    use HasFactory;
    use SoftDeletes;
    use HasSorting;

    protected $table = 'bpms_workflow_states';

    protected $fillable = [
        'workflow_id',
        'name',
        'slug',
        'description',
        'color',
        'order',
        'position',
        'default_assignee_id',
        'is_active',
    ];

    protected $casts = [
        'position'  => WorkflowStatePosition::class,
        'is_active' => 'boolean',
    ];

    public function sortingColumnName(): string
    {
        return 'order';
    }

    public function baseSortQuery(): Builder
    {
        return static::query()->where('workflow_id', $this->workflow_id);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function defaultAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_assignee_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'current_state_id');
    }

    protected static function newFactory(): WorkflowStateFactory
    {
        return WorkflowStateFactory::new();
    }
}
