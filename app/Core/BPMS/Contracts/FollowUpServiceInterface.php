<?php

declare(strict_types=1);

namespace App\Core\BPMS\Contracts;

use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\Entities\FollowUp;

interface FollowUpServiceInterface
{
    public function create(FollowUpDTO $dto, ?int $previousAssigneeId = null, ?int $previousStateId = null): FollowUp;
}
