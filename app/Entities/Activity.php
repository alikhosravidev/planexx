<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Model\BaseModelContract;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity implements BaseModelContract
{
}
