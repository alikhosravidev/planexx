<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Http\Requests\V1\Admin\StoreTaskRequest;
use App\Core\BPMS\Http\Requests\V1\Admin\UpdateTaskRequest;
use App\Core\BPMS\Http\Transformers\V1\Admin\TaskTransformer;
use App\Core\BPMS\Mappers\TaskMapper;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskAPIController extends BaseAPIController
{
    public function __construct(
        TaskRepository                  $repository,
        TaskTransformer                 $transformer,
        private readonly TaskService    $service,
        private readonly TaskMapper     $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $dto  = $this->mapper->fromStoreRequest($request);
        $task = $this->service->create($dto);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($task),
                ['redirect_url' => route('web.bpms.tasks.show', $task->id)],
            ),
            'کار جدید با موفقیت ایجاد شد.',
        );
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        /** @var Task $task */
        $task = $this->repository->findOrFail($id);

        $actionDTO = $this->mapper->toUpdateActionDTO($request, $task);
        $updated   = $this->service->processUpdate($task, $actionDTO);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            $actionDTO->action->getSuccessMessage(),
        );
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Task $task */
        $task = $this->repository->findOrFail($id);
        $task->delete();

        return $this->response->success([], 'کار مورد نظر حذف شد.');
    }
}
