<?php

declare(strict_types=1);

namespace App\Core\BPMS\Events;

use App\Core\BPMS\Entities\Workflow;
use Illuminate\Foundation\Events\Dispatchable;

final class WorkflowCreated
{
    use Dispatchable;

    public function __construct(public Workflow $workflow)
    {
    }
}
