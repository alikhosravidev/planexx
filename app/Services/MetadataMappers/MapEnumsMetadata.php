<?php

declare(strict_types=1);

namespace App\Services\MetadataMappers;

use App\Services\Fetcher\BaseFetcher;
use App\Services\Fetcher\FetchEnums;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;

class MapEnumsMetadata extends BaseMetadataMapper
{
    protected static self $instance;

    protected static function getFetcher(): BaseFetcher
    {
        return new FetchEnums();
    }

    public static function getEnumNamespace(string $enum): string
    {
        $enums     = self::getInstance()->getMap();
        $enum      = Str::studly($enum);
        $enumClass = $enums[$enum] ?? null;

        if (
            is_numeric($enumClass)
            || ! class_exists($enumClass)
            || ! (new ReflectionClass($enumClass))->isEnum()
        ) {
            throw new InvalidArgumentException('اینام مورد نظر یافت نشد!');
        }

        return $enumClass;
    }

    protected function getCachedMapPath(): string
    {
        return $this->basePath . '/bootstrap/cache/enums_map.php';
    }

    protected function normalizeMap(array $data): array
    {
        return collect($data)
            ->pluck('namespace', 'name')
            ->toArray()
        ;
    }
}
