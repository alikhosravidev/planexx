<?php

declare(strict_types=1);

namespace App\Services\MetadataMappers;

use App\Services\Fetcher\BaseFetcher;
use Exception;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

abstract class BaseMetadataMapper
{
    protected array $map = [];

    protected array $metadata = [];

    protected function __construct(
        protected readonly Filesystem   $files,
        protected readonly BaseFetcher  $fetcher,
        protected readonly string       $basePath,
    ) {
        if (! $this->setMap()) {
            throw new RuntimeException('Failed to set entities map');
        }
    }

    abstract protected static function getFetcher(): BaseFetcher;

    public static function getInstance(?string $basePath = null): static
    {
        if (! isset(static::$instance) || empty(static::$instance)) {
            static::$instance = new static(
                new Filesystem(),
                static::getFetcher(),
                $basePath ?? base_path(),
            );

            return static::$instance;
        }

        return static::$instance;
    }

    abstract protected function getCachedMapPath(): string;

    abstract protected function normalizeMap(array $data): array;

    public function getMetadata(): array
    {
        if (empty($this->metadata)) {
            $this->metadata = $this->fetcher->handle($this->basePath);
        }

        return $this->metadata;
    }

    protected function setMap(): bool
    {
        if ($this->isCached()) {
            return true;
        }

        $this->map = $this->normalizeMap(
            $this->getMetadata()
        );

        $this->files->put(
            $this->getCachedMapPath(),
            '<?php return ' . var_export($this->map, true) . ';' . PHP_EOL
        );

        return ! empty($this->map);
    }

    protected function isCached(): bool
    {
        $cacheFile = $this->getCachedMapPath();

        if (! file_exists($cacheFile)) {
            return false;
        }

        $map = require $cacheFile;

        if (empty($map) || ! is_array($map)) {
            return false;
        }

        $this->map = $map;

        return true;
    }

    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new Exception('Cannot unserialize a EntitiesMapService.');
    }

    public function getMap(): array
    {
        return $this->map;
    }

    protected function removeCached(): bool
    {
        $cached = $this->getCachedMapPath();

        if (!file_exists($cached)) {
            return true;
        }

        return $this->files->delete($cached);
    }

    public function reset(): bool
    {
        $this->removeCached();
        $result = $this->setMap();

        if (!$result) {
            throw new RuntimeException('Failed to set entities map');
        }

        return true;
    }
}
