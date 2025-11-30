<?php

declare(strict_types=1);

namespace App\Services\Fetcher;

use ImanGhafoori\ComposerJson\ComposerJson;
use Symfony\Component\Finder\Finder;

abstract class BaseFetcher
{
    abstract protected function ignoreList(): array;

    abstract protected function isValidItem(string $namespace, array $includes, array $excludes): bool;

    abstract protected function getInfoItem(string $namespace): string|array;

    public function handle(string $basePath, array $includes = [], array $excludes = []): array
    {
        $autoloader = ComposerJson::make($basePath)->readAutoload();

        $items = [];

        foreach ($autoloader as $autoload) {
            foreach ($autoload as $baseNamespace => $psr4Path) {
                $path = $basePath . '/' . $psr4Path;

                foreach ($this->getFiles($path) as $file) {
                    $namespace = $this->getNamespace($baseNamespace, $file);

                    if ($this->isValidItem($namespace, $includes, $excludes)) {
                        $items[] = $this->getInfoItem($namespace);
                    }
                }
            }
        }

        return $items;
    }

    private function getFiles(string $path): Finder
    {
        return Finder::create()->files()->name('*.php')->in($path);
    }

    protected function getNamespace(string $baseNamespace, mixed $file): string
    {
        $namespace = str_replace(
            '/',
            '\\',
            $baseNamespace . $file->getRelativePathname()
        );

        return str_replace('.php', '', $namespace);
    }

    protected function checkIncludesAndExcludes(string $namespace, array $includes, array $excludes): bool
    {
        if (! empty($includes) && ! str($namespace)->contains($includes)) {
            return false;
        }

        if (! empty($excludes) && str($namespace)->contains($excludes)) {
            return false;
        }

        return true;
    }

    protected function isValidClass(string $namespace): bool
    {
        if (str_contains($namespace, '\\Routes\\')
            || str_contains($namespace, '\\Config\\')) {
            return false;
        }

        return class_exists($namespace);
    }
}
