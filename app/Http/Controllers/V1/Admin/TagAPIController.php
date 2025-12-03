<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Http\Requests\V1\Admin\StoreTagRequest;
use App\Http\Requests\V1\Admin\UpdateTagRequest;
use App\Http\Transformers\V1\Admin\TagTransformer;
use App\Mappers\TagMapper;
use App\Repositories\TagRepository;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagAPIController extends BaseAPIController
{
    public function __construct(
        TagRepository                $repository,
        TagTransformer               $transformer,
        private readonly TagService  $service,
        private readonly TagMapper   $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreTagRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);
        $tag = $this->service->create($dto);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($tag),
                ['redirect_url' => route('web.app.tags.edit', $tag->id)]
            ),
            'برچسب با موفقیت ایجاد شد.'
        );
    }

    public function update(UpdateTagRequest $request, int $id): JsonResponse
    {
        $tag     = $this->repository->findOrFail($id);
        $dto     = $this->mapper->fromRequestForUpdate($request, $tag);
        $updated = $this->service->update($tag, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'برچسب با موفقیت بروزرسانی شد.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $tag = $this->repository->findOrFail($id);
        $this->service->delete($tag);

        return $this->response->success([], 'برچسب مورد نظر حذف شد.');
    }
}
