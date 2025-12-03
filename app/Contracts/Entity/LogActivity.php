<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;

interface LogActivity
{
    public function getActivitylogOptions(): LogOptions;

    public function activities(): MorphMany;
}
