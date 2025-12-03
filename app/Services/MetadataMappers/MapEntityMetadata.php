<?php

declare(strict_types=1);

namespace App\Services\MetadataMappers;

use App\Contracts\Entity\EntityInterface;
use App\Services\Fetcher\BaseFetcher;
use App\Services\Fetcher\FetchEntities;
use Illuminate\Database\Eloquent\Relations\Relation;

class MapEntityMetadata extends BaseMetadataMapper
{
    protected static self $instance;

    public static function enforceMorphMap(): bool
    {
        $map = self::getInstance()->getMap();

        if (empty($map)) {
            throw new \RuntimeException('No entities map found');
        }

        Relation::enforceMorphMap($map);

        return true;
    }

    public static function allTableName(): array
    {
        $map = self::getInstance()->getMap();

        return array_keys($map);
    }

    public static function getNamespaceModel($tableName): ?string
    {
        $map = self::getInstance()->getMap();

        if (
            empty($map[$tableName])
            || ! is_subclass_of($map[$tableName], EntityInterface::class)
        ) {
            return null;
        }

        return $map[$tableName];
    }

    public static function getEntity(?string $entityType, null|int|string $entityId): ?EntityInterface
    {
        if (null === $entityType || ! is_numeric($entityId)) {
            return null;
        }

        $namespace = self::getNamespaceModel($entityType);

        if (null === $namespace) {
            return null;
        }

        return $namespace::query()->find($entityId);
    }

    protected static function getFetcher(): BaseFetcher
    {
        return new FetchEntities();
    }

    protected function getCachedMapPath(): string
    {
        return $this->basePath . '/bootstrap/cache/morph_map_entities.php';
    }

    protected function normalizeMap(array $data): array
    {
        return collect($data)
            ->pluck('namespace', 'table_name')
            ->toArray()
        ;
    }
}
