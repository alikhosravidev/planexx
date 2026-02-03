<?php

declare(strict_types=1);

namespace App\Core\BPMS\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\BPMS\Database\Factories\WorkflowFactory;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\Role;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use App\Core\Organization\Traits\HasOwner;
use App\Core\Organization\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

/**
 * @property int                 $id
 * @property string              $name
 * @property string|null         $slug
 * @property string|null         $description
 * @property int|null            $department_id
 * @property bool                $is_active
 * @property float               $estimated_hours
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * Relations:
 * @property Department|null $department
 * @property \Illuminate\Database\Eloquent\Collection<int, WorkflowState> $states
 * @property \Illuminate\Database\Eloquent\Collection<int, Role> $allowedRoles
 */
class Workflow extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;
    use HasCreator;
    use HasOwner;
    use HasRoles;
    use HasPermissions;

    public const TABLE = 'bpms_workflows';
    protected $table   = self::TABLE;

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
        'is_active'       => 'boolean',
        'estimated_hours' => 'float',
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
        return $this->hasMany(WorkflowState::class, 'workflow_id')
            ->orderBy('position')->orderBy('order');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'workflow_id');
    }

    public function allowedRoles()
    {
        return $this->roles();
    }

    protected static function newFactory(): WorkflowFactory
    {
        return WorkflowFactory::new();
    }
}
