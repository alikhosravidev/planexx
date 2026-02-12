<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\FileManager\Http\Requests\V1\Admin\StoreFolderRequest;
use App\Core\FileManager\Http\Requests\V1\Admin\UpdateFolderRequest;
use App\Core\FileManager\Http\Transformers\V1\Admin\FolderTransformer;
use App\Core\FileManager\Mappers\FolderMapper;
use App\Core\FileManager\Repositories\FolderRepository;
use App\Core\FileManager\Services\FolderService;
use Illuminate\Http\JsonResponse;

class FolderController extends BaseAPIController
{
    public function __construct(
        FolderRepository               $repository,
        FolderTransformer              $transformer,
        private readonly FolderService $service,
        private readonly FolderMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreFolderRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    public function update(UpdateFolderRequest $request, int $id): JsonResponse
    {
        $folder = $this->repository->findOrFail($id);
        $dto    = $this->mapper->fromRequestForUpdate($request, $folder);

        $updated = $this->service->update($folder, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $folder = $this->repository->findOrFail($id);
        $this->service->delete($folder);

        return $this->response->success([], 'پوشه با موفقیت ایجاد شد.');
    }
}
