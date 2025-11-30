<?php

declare(strict_types=1);

namespace App\Services\Fetcher;

use App\Contracts\Model\BaseModel;
use App\Contracts\Model\BaseModelContract;

class FetchModelEntities extends BaseFetcher
{
    protected function isValidItem(
        string $namespace,
        array  $includes = [],
        array  $excludes = []
    ): bool {
        if (! $this->checkIncludesAndExcludes($namespace, $includes, $excludes)) {
            return false;
        }

        if (! $this->isValidClass($namespace)) {
            return false;
        }

        return is_subclass_of($namespace, BaseModelContract::class)
            && ! in_array($namespace, $this->ignoreList())
        ;
    }

    protected function ignoreList(): array
    {
        return [
            BaseModel::class,
            BaseModelContract::class,
        ];
    }

    protected function getInfoItem(string $namespace): array
    {
        $parts = explode('\\', $namespace);

        return [
            'namespace'  => $namespace,
            'table_name' => (new $namespace())->getTable(),
            'module'     => $parts[2] ?? null,
        ];
    }
}
