<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Database\Factories\UserDepartmentFactory;
use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                          $id
 * @property int                          $user_id
 * @property int                          $department_id
 * @property bool                         $is_primary
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 *
 * Relations:
 * @property User                         $user
 * @property Department                   $department
 */
class UserDepartment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public $incrementing = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    protected static function newFactory(): UserDepartmentFactory
    {
        return UserDepartmentFactory::new();
    }
}
