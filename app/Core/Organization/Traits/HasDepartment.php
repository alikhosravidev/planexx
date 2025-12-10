<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\Department;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasDepartment
{
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'core_org_user_departments',
            'user_id',
            'department_id'
        )->withPivot('is_primary')->withTimestamps();
    }
}
