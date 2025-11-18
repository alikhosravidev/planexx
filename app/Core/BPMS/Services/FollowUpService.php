<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\FollowUpServiceInterface;
use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\Entities\FollowUp;
use App\Core\BPMS\Repositories\FollowUpRepository;

readonly class FollowUpService implements FollowUpServiceInterface
{
    public function __construct(
        private FollowUpRepository $followUpRepository,
    ) {
    }

    public function create(FollowUpDTO $dto, ?int $previousAssigneeId = null, ?int $previousStateId = null): FollowUp
    {
        $data = $dto->toArray();

        $data['previous_assignee_id'] = $previousAssigneeId;
        $data['previous_state_id']    = $previousStateId;
        $data['created_at']           = now();

        return $this->followUpRepository->create($data);
    }
}
