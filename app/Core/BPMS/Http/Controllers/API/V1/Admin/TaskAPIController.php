<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Http\Requests\StoreTaskRequest;
use App\Core\BPMS\Http\Requests\UpdateTaskRequest;
use App\Core\BPMS\Http\Transformers\TaskTransformer;
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

        $dto     = $this->mapper->fromUpdateRequest($request, $task);
        $updated = $this->service->update($task, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'کار مورد نظر بروزرسانی شد.',
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
