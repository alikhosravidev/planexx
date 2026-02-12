<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Http\Requests\V1\Admin\StoreWorkflowRequest;
use App\Core\BPMS\Http\Requests\V1\Admin\UpdateWorkflowRequest;
use App\Core\BPMS\Http\Transformers\V1\Admin\WorkflowTransformer;
use App\Core\BPMS\Mappers\WorkflowMapper;
use App\Core\BPMS\Repositories\WorkflowRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use App\Core\BPMS\Services\WorkflowService;
use App\Core\BPMS\Services\WorkflowStateService;
use Illuminate\Http\JsonResponse;

class AdminWorkflowController extends BaseAPIController
{
    public function __construct(
        WorkflowRepository              $repository,
        WorkflowTransformer             $transformer,
        private readonly WorkflowService $service,
        private readonly WorkflowMapper   $mapper,
        private readonly WorkflowStateService $stateService,
        private readonly WorkflowStateRepository $stateRepository,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreWorkflowRequest $request): JsonResponse
    {
        $dto      = $this->mapper->fromStoreRequest($request);
        $workflow = $this->service->create($dto);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($workflow),
                ['redirect_url' => route('web.bpms.workflows.edit', $workflow->id)],
            ),
            'فرایند مورد نظر ذخیره شد.',
        );
    }

    public function update(UpdateWorkflowRequest $request, int $id): JsonResponse
    {
        /** @var Workflow $workflow */
        $workflow = $this->repository->findOrFail($id);

        $dto     = $this->mapper->fromUpdateRequest($request, $workflow);
        $updated = $this->service->update($workflow, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'فرایند مورد نظر بروزرسانی شد.',
        );
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Workflow $workflow */
        $workflow = $this->repository->findOrFail($id);
        $this->service->delete($workflow);

        return $this->response->success([], 'فرایند مورد نظر حذف شد.');
    }
}
