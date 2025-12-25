<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Http\Requests\StoreFollowUpRequest;
use App\Core\BPMS\Http\Transformers\FollowUpTransformer;
use App\Core\BPMS\Mappers\FollowUpMapper;
use App\Core\BPMS\Repositories\FollowUpRepository;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Services\FollowUpService;
use Illuminate\Http\JsonResponse;

class FollowUpAPIController extends BaseAPIController
{
    public function __construct(
        FollowUpRepository                $repository,
        FollowUpTransformer               $transformer,
        private readonly FollowUpService  $service,
        private readonly FollowUpMapper   $mapper,
        private readonly TaskRepository   $taskRepository,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreFollowUpRequest $request): JsonResponse
    {
        /** @var Task $task */
        $task = $this->taskRepository->findOrFail((int) $request->input('task_id'));

        $dto      = $this->mapper->fromStoreRequest($request);
        $followUp = $this->service->create(
            dto               : $dto,
            previousAssigneeId: $task->assignee_id,
            previousStateId   : $task->current_state_id,
            attachment        : $request->file('attachment'),
        );

        if ($request->filled('next_follow_up_date')) {
            $task->update([
                'next_follow_up_date' => $request->input('next_follow_up_date'),
            ]);
        }

        return $this->response->created(
            $this->transformer->transformOne($followUp),
            'یادداشت با موفقیت ثبت شد.',
        );
    }
}
