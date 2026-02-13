<?php

declare(strict_types=1);

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Modules\Product\DTOs\CustomListDTO;
use Modules\Product\Entities\CustomList;
use Modules\Product\Exceptions\CustomListException;
use Modules\Product\Repositories\CustomListRepository;
use Modules\Product\Services\CustomList\Actions\ResolveSlugAction;
use Modules\Product\Services\CustomList\Actions\SyncAttributesAction;
use Modules\Product\Services\CustomList\Validation\DuplicateAttributeKeyValidator;

readonly class CustomListService
{
    public function __construct(
        private CustomListRepository $customListRepository,
        private DuplicateAttributeKeyValidator $duplicateKeyValidator,
        private SyncAttributesAction $syncAttributes,
        private ResolveSlugAction $resolveSlug,
    ) {
    }

    /**
     * @throws CustomListException|\Throwable
     */
    public function create(CustomListDTO $dto, int $createdBy): CustomList
    {
        return DB::transaction(function () use ($dto, $createdBy) {
            $this->duplicateKeyValidator->validate($dto->attributes);

            $data               = $dto->toArray();
            $data['slug']       = $this->resolveSlug->execute($dto);
            $data['created_by'] = $createdBy;

            $list = $this->customListRepository->create($data);

            $this->syncAttributes->execute($list, $dto->attributes);

            return $list->load('attributes');
        });
    }

    /**
     * @throws CustomListException|\Throwable
     */
    public function update(CustomList $list, CustomListDTO $dto): CustomList
    {
        return DB::transaction(function () use ($list, $dto) {
            $this->duplicateKeyValidator->validate($dto->attributes);

            $data         = $dto->toArray();
            $data['slug'] = $this->resolveSlug->execute($dto, $list);

            $list = $this->customListRepository->update($list->id, $data);

            if (! $dto->attributes->isEmpty()) {
                $this->syncAttributes->execute($list, $dto->attributes);
            }

            return $list->load('attributes');
        });
    }

    /**
     * Delete relies on database cascade constraints:
     * - Attributes are deleted via FK cascade
     * - Items are deleted via FK cascade
     * - Values are deleted via FK cascade from items
     *
     * @throws \Throwable
     */
    public function delete(CustomList $list): bool
    {
        return $this->customListRepository->delete($list->id);
    }
}
