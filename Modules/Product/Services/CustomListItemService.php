<?php

declare(strict_types=1);

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Modules\Product\DTOs\CustomListItemDTO;
use Modules\Product\Entities\CustomListItem;
use Modules\Product\Repositories\CustomListItemRepository;
use Modules\Product\Repositories\CustomListRepository;
use Modules\Product\Services\CustomList\Actions\SaveItemValuesAction;
use Modules\Product\Services\CustomList\Validation\ItemValuesValidator;

readonly class CustomListItemService
{
    public function __construct(
        private CustomListItemRepository $customListItemRepository,
        private CustomListRepository $customListRepository,
        private ItemValuesValidator $itemValuesValidator,
        private SaveItemValuesAction $saveValues,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function create(CustomListItemDTO $dto, int $createdBy): CustomListItem
    {
        return DB::transaction(function () use ($dto, $createdBy) {
            $list = $this->customListRepository->findOrFail($dto->listId->value);

            $this->itemValuesValidator->validate($list->attributes()->get(), $dto->values);

            $data               = $dto->toArray();
            $data['created_by'] = $createdBy;

            $item = $this->customListItemRepository->create($data);

            $this->saveValues->execute($item, $dto->values);

            return $item->load('values');
        });
    }

    /**
     * @throws \Throwable
     */
    public function update(CustomListItem $item, CustomListItemDTO $dto): CustomListItem
    {
        return DB::transaction(function () use ($item, $dto) {
            $this->itemValuesValidator->validate($item->list->attributes()->get(), $dto->values);

            $this->customListItemRepository->update($item->id, [
                'reference_code' => $dto->referenceCode,
            ]);

            $item->values()->delete();
            $this->saveValues->execute($item, $dto->values);

            return $item->fresh('values');
        });
    }

    public function delete(CustomListItem $item): bool
    {
        return $this->customListItemRepository->delete($item->id);
    }
}
