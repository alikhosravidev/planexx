<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Entity\EntityInterface;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity implements EntityInterface
{
    public const DEFAULT_LOG_NAME = 'entity-events';
}
