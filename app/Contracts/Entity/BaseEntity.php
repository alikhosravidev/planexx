<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use App\Services\MetadataMappers\MapEntityMetadata;
use App\Traits\LogsEntityActivity;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEntity extends Model implements
    EntityInterface,
    LogActivity
{
    use LogsEntityActivity;

    public array $protectedFields = [];

    public function getMorphClass(): string
    {
        try {
            return parent::getMorphClass();
        } catch (ClassMorphViolationException $exception) {
            MapEntityMetadata::enforceMorphMap();

            return $this->getTable();
        }
    }

    public function getModuleName(): string
    {
        $namespace = get_class($this);
        $parts     = explode('\\', $namespace);

        if (! isset($parts[1], $parts[2])) {
            return '';
        }

        if (! in_array($parts[1], ['Modules', 'Core'], true)) {
            return '';
        }

        $rawName = $parts[2];

        if ($rawName === '') {
            return '';
        }

        return strtoupper($rawName) === $rawName
            ? str($rawName)->lower()->toString()
            : str($rawName)->kebab()->toString();
    }

    /*protected function asJson($value)
    {
        return json_encode(
            $value,
            JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE
        );
    }*/
}
