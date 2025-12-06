<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\FileManager\Entities\File;
use App\Core\Organization\Database\Factories\DepartmentFactory;
use App\Core\Organization\Enums\DepartmentTypeEnum;
use App\Core\Organization\Traits\HasManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                         $id
 * @property int|null                    $parent_id
 * @property string                      $name
 * @property string|null                 $code
 * @property string|null                 $image_url
 * @property string|null                 $description
 * @property DepartmentTypeEnum          $type
 * @property bool                        $is_active
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property Department|null              $parent
 * @property \Illuminate\Database\Eloquent\Collection<int, Department> $children
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Core\Organization\Entities\User> $users
 * @property File|null                    $image
 */
class Department extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;
    use HasManager;

    protected bool $shouldLogActivity = true;

    public const TABLE = 'core_org_departments';

    protected $table = self::TABLE;

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'manager_id',
        'image_url',
        'color',
        'icon',
        'description',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type'      => DepartmentTypeEnum::class,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'core_org_user_departments')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function image(): MorphOne
    {
        return $this->morphOne(File::class, 'entity');
    }

    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }
}
