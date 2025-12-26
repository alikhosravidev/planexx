<?php

declare(strict_types=1);

namespace App\Core\BPMS\Mappers;

use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\Enums\FollowUpType;
use App\Core\BPMS\Http\Requests\V1\Admin\StoreFollowUpRequest;
use App\Domains\Task\TaskId;
use App\Domains\User\UserId;

class FollowUpMapper
{
    public function fromStoreRequest(StoreFollowUpRequest $request): FollowUpDTO
    {
        return new FollowUpDTO(
            taskId   : new TaskId((int) $request->input('task_id')),
            type     : FollowUpType::from((int) $request->input('type', FollowUpType::FOLLOW_UP->value)),
            createdBy: new UserId($request->user()->id),
            content  : $request->input('content'),
        );
    }
}
