<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\FileManager\Http\Requests\V1\Admin\UpdateFileRequest;
use App\Core\FileManager\Http\Requests\V1\Admin\UploadFileRequest;
use App\Core\FileManager\Http\Transformers\V1\Admin\FileTransformer;
use App\Core\FileManager\Mappers\FileMapper;
use App\Core\FileManager\Repositories\FileRepository;
use App\Core\FileManager\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileAPIController extends BaseAPIController
{
    public function __construct(
        FileRepository               $repository,
        FileTransformer              $transformer,
        private readonly FileService $service,
        private readonly FileMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(UploadFileRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromUploadRequest($request);

        $model = $this->service->upload($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    public function update(UpdateFileRequest $request, int $id): JsonResponse
    {
        $file = $this->repository->findOrFail($id);
        $dto  = $this->mapper->fromUpdateRequest($request);

        $updated = $this->service->update($file, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $file = $this->repository->findOrFail($id);
        $this->service->delete($file);

        return $this->response->success([]);
    }

    public function download(int $id): StreamedResponse|JsonResponse
    {
        $file = $this->repository->findOrFail($id);

        if (!$file->is_public && !auth()->check()) {
            return $this->response->error('Unauthorized', 401);
        }

        $this->service->incrementDownloadCount($file);

        $url = $this->service->getDownloadUrl($file);

        return redirect($url);
    }

    public function show(int|string $id, Request $request): JsonResponse
    {
        $file = $this->repository->findOrFail($id);

        $this->service->incrementViewCount($file);

        return parent::show($id, $request);
    }
}
