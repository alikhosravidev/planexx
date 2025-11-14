<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\BPMS\Database\Factories\WorkflowFactory;
use App\Core\Organization\Entities\Department;
use App\Core\User\Entities\User;
use App\Core\User\Traits\HasCreator;
use App\Core\User\Traits\HasOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

/**
 * @property int                         $id
 * @property string                      $name
 * @property string|null                 $slug
 * @property string|null                 $description
 * @property int|null                    $department_id
 * @property bool                        $is_active
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property Department|null             $department
 * @property \Illuminate\Database\Eloquent\Collection<int, WorkflowState> $states
 * @property \Illuminate\Database\Eloquent\Collection<int, Role> $allowedRoles
 */
class Workflow extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use HasCreator;
    use HasOwner;

    protected $table = 'bpms_workflows';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'department_id',
        'owner_id',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function states(): HasMany
    {
        return $this->hasMany(WorkflowState::class, 'workflow_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'workflow_id');
    }

    public function allowedRoles(): MorphToMany
    {
        return $this->morphToMany(
            related: Role::class,
            name: 'model',
            table: 'model_has_roles',
            foreignPivotKey: 'model_id',
            relatedPivotKey: 'role_id'
        );
    }

    protected static function newFactory(): WorkflowFactory
    {
        return WorkflowFactory::new();
    }
}
