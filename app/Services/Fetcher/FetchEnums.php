<?php

declare(strict_types=1);

namespace App\Services\Fetcher;

class FetchEnums extends BaseFetcher
{
    protected function ignoreList(): array
    {
        return [];
    }

    /**
     * @throws \ReflectionException
     */
    protected function isValidItem(string $namespace, array $includes, array $excludes): bool
    {
        if (! $this->checkIncludesAndExcludes($namespace, $includes, $excludes)) {
            return false;
        }

        if (! $this->isValidClass($namespace)) {
            return false;
        }

        return ! in_array($namespace, $this->ignoreList())
            && (new \ReflectionClass($namespace))->isEnum();
    }

    protected function getInfoItem(string $namespace): string|array
    {
        $parts = explode('\\', $namespace);

        return [
            'namespace' => $namespace,
            'name'      => end($parts),
            'module'    => $parts[2] ?? null,
        ];
    }
}
