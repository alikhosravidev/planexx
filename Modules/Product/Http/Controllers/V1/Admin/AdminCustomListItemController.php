<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Entities\CustomListItem;
use Modules\Product\Http\Requests\V1\Admin\StoreCustomListItemRequest;
use Modules\Product\Http\Requests\V1\Admin\UpdateCustomListItemRequest;
use Modules\Product\Http\Transformers\V1\Admin\CustomListItemTransformer;
use Modules\Product\Mappers\CustomListItemMapper;
use Modules\Product\Repositories\CustomListItemRepository;
use Modules\Product\Services\CustomListItemService;

class AdminCustomListItemController extends BaseAPIController
{
    public function __construct(
        CustomListItemRepository                    $repository,
        CustomListItemTransformer                   $transformer,
        private readonly CustomListItemService      $service,
        private readonly CustomListItemMapper       $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    protected function customizeQuery(Builder $query, Request $request): Builder
    {
        $listId = (int) $request->route()->parameter('custom_list');

        return $query->where('list_id', $listId);
    }

    public function store(StoreCustomListItemRequest $request, int $customList): JsonResponse
    {
        $dto  = $this->mapper->fromRequest($request, $customList);
        $item = $this->service->create($dto, $request->user()->id);

        return $this->response->created(
            $this->transformer->transformOne($item),
            'آیتم جدید با موفقیت ایجاد شد.',
        );
    }

    public function update(UpdateCustomListItemRequest $request): JsonResponse
    {
        $id = (int) $request->route()->parameter('item');

        /** @var CustomListItem $item */
        $item = $this->repository->findOrFail($id);

        $dto     = $this->mapper->fromRequestForUpdate($request, $item);
        $updated = $this->service->update($item, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'آیتم با موفقیت بروزرسانی شد.',
        );
    }

    public function destroy(Request $request): JsonResponse
    {
        $id = (int) $request->route()->parameter('item');

        /** @var CustomListItem $item */
        $item = $this->repository->findOrFail($id);
        $this->service->delete($item);

        return $this->response->success([], 'آیتم مورد نظر حذف شد.');
    }
}
