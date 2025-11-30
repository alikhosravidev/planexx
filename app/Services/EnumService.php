<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\MetadataMappers\MapEnumsMetadata;
use App\Services\Transformer\FieldTransformers\EnumTransformer;
use Illuminate\Support\Arr;

class EnumService
{
    public function __construct(
        private readonly EnumTransformer $enumTransformer
    ) {
    }

    public function getData(string $enum): array
    {
        $enumClass = MapEnumsMetadata::getEnumNamespace($enum);

        return $this->enumTransformer
            ->transformMany($enumClass::cases());
    }

    /**
     * Gets a filtered key-value list for a given enum.
     *
     * @param  string  $enumName  The name of the enum to fetch.
     * @param  string|null  $searchQuery  The search query string (e.g., "key1:value1;key2:value2").
     * @param  string  $value
     * @return array
     */
    public function getFilteredKeyValList(string $enumName, ?string $searchQuery, string $value = 'value'): array
    {
        $enumClass = MapEnumsMetadata::getEnumNamespace($enumName);

        $transformedEnums = $this->enumTransformer
            ->transformMany($enumClass::cases());

        if (empty($searchQuery)) {
            return array_column($transformedEnums, 'label', $value);
        }

        return $this->filterEnums($transformedEnums, $searchQuery);
    }

    /**
     * Filters the transformed enum data based on the search query.
     *
     * @param  array  $enums
     * @param  string  $searchQuery
     * @return array
     */
    private function filterEnums(array $enums, string $searchQuery): array
    {
        $data           = [];
        $searchCriteria = $this->parseSearchQuery($searchQuery);

        foreach ($enums as $enum) {
            foreach ($searchCriteria as $key => $searchValue) {
                if (Arr::has($enum, $key) && str_contains((string) Arr::get($enum, $key), $searchValue)) {
                    $data[$enum['value']] = $enum['to_persian'];
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Parses the multi-key search query string into an associative array.
     *
     * @param  string  $query
     * @return array
     */
    private function parseSearchQuery(string $query): array
    {
        $criteria = [];
        $pairs    = explode(';', $query);

        foreach ($pairs as $pair) {
            $parts = explode(':', $pair, 2);

            if (count($parts) === 2 && ! empty($parts[0])) {
                $criteria[$parts[0]] = $parts[1];
            }
        }

        return $criteria;
    }
}
