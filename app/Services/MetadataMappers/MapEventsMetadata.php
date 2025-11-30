<?php

declare(strict_types=1);

namespace App\Services\MetadataMappers;

use App\Services\Fetcher\BaseFetcher;
use App\Services\Fetcher\FetchBusinessEvents;

class MapEventsMetadata extends BaseMetadataMapper
{
    protected static self $instance;

    protected static function getFetcher(): BaseFetcher
    {
        return new FetchBusinessEvents();
    }

    protected function getCachedMapPath(): string
    {
        return $this->basePath . '/bootstrap/cache/events_map.php';
    }

    protected function normalizeMap(array $data): array
    {
        return $data;
    }
}
