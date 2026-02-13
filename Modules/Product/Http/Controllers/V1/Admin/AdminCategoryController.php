<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\Category;
use Modules\Product\Http\Requests\V1\Admin\StoreCategoryRequest;
use Modules\Product\Http\Requests\V1\Admin\UpdateCategoryRequest;
use Modules\Product\Http\Transformers\V1\Admin\CategoryTransformer;
use Modules\Product\Mappers\CategoryMapper;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Services\CategoryService;

class AdminCategoryController extends BaseAPIController
{
    public function __construct(
        CategoryRepository                    $repository,
        CategoryTransformer                   $transformer,
        private readonly CategoryService      $service,
        private readonly CategoryMapper       $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $dto      = $this->mapper->fromRequest($request);
        $category = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($category),
            'دسته‌بندی جدید با موفقیت ایجاد شد.',
        );
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->repository->findOrFail($id);

        $dto     = $this->mapper->fromRequestForUpdate($request, $category);
        $updated = $this->service->update($category, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'دسته‌بندی با موفقیت بروزرسانی شد.',
        );
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->repository->findOrFail($id);
        $this->service->delete($category);

        return $this->response->success([], 'دسته‌بندی مورد نظر حذف شد.');
    }
}
