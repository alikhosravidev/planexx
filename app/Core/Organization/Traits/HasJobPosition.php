<?php

declare(strict_types=1);

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\JobPosition;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $job_position_id
 *
 * Relations:
 * @property JobPosition|null $jobPosition
 */
trait HasJobPosition
{
    public function getFillable(): array
    {
        return array_merge($this->fillable, ['job_position_id']);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }
}
