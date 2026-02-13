<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\CustomList;
use Modules\Product\Http\Requests\V1\Admin\StoreCustomListRequest;
use Modules\Product\Http\Requests\V1\Admin\UpdateCustomListRequest;
use Modules\Product\Http\Transformers\V1\Admin\CustomListTransformer;
use Modules\Product\Mappers\CustomListMapper;
use Modules\Product\Repositories\CustomListRepository;
use Modules\Product\Services\CustomListService;

class AdminCustomListController extends BaseAPIController
{
    public function __construct(
        CustomListRepository                    $repository,
        CustomListTransformer                   $transformer,
        private readonly CustomListService      $service,
        private readonly CustomListMapper       $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreCustomListRequest $request): JsonResponse
    {
        $dto  = $this->mapper->fromRequest($request);
        $list = $this->service->create($dto, $request->user()->id);

        return $this->response->created(
            $this->transformer->transformOne($list),
            'لیست جدید با موفقیت ایجاد شد.',
        );
    }

    public function update(UpdateCustomListRequest $request, int $id): JsonResponse
    {
        /** @var CustomList $list */
        $list = $this->repository->findOrFail($id);

        $dto     = $this->mapper->fromRequestForUpdate($request, $list);
        $updated = $this->service->update($list, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'لیست با موفقیت بروزرسانی شد.',
        );
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var CustomList $list */
        $list = $this->repository->findOrFail($id);
        $this->service->delete($list);

        return $this->response->success([], 'لیست مورد نظر حذف شد.');
    }
}
