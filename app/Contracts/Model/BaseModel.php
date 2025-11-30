<?php

declare(strict_types=1);

namespace App\Contracts\Model;

use App\Traits\LogsModelActivity;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model implements BaseModelContract
{
    use LogsModelActivity;

    /*    public function getMorphClass(): string
        {
            try {
                return parent::getMorphClass();
            } catch (ClassMorphViolationException $exception) {
                MapModelsMetadata::enforceMorphMap();

                return $this->getTable();
            }
        }*/
}
