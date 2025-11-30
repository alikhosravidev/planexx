<?php

declare(strict_types=1);

namespace App\Services\Fetcher;

use App\Contracts\BusinessEvent;
use ReflectionClass;

class FetchBusinessEvents extends BaseFetcher
{
    /**
     * @throws \ReflectionException
     */
    protected function isValidItem(
        string $namespace,
        array  $includes,
        array  $excludes
    ): bool {
        if (! $this->checkIncludesAndExcludes($namespace, $includes, $excludes)) {
            return false;
        }

        if (! $this->isValidClass($namespace)) {
            return false;
        }

        return ! in_array($namespace, $this->ignoreList())
            && is_subclass_of($namespace, BusinessEvent::class)
            && ! (new ReflectionClass($namespace))->isAbstract();
    }

    protected function ignoreList(): array
    {
        return [
            BusinessEvent::class,
        ];
    }

    protected function getInfoItem(string $namespace): string
    {
        return $namespace;
    }
}
