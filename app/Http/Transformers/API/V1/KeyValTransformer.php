<?php

declare(strict_types=1);

namespace App\Http\Transformers\API\V1;

use App\Contracts\Model\BaseModelContract;
use App\Contracts\Transformer\BaseTransformer;
use App\Contracts\Transformer\DataExtractorInterface;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\TransformerConfig;
use League\Fractal\Manager;
use Psr\Log\LoggerInterface;

class KeyValTransformer extends BaseTransformer
{
    public function __construct(
        TransformerConfig $config,
        FieldTransformerRegistry $registry,
        DataExtractorInterface $extractor,
        Manager $manager,
        LoggerInterface $logger,
        private string $field,
        private string $key
    ) {
        parent::__construct($config, $registry, $extractor, $manager, $logger);
    }

    public function transform(BaseModelContract $model): array
    {
        return [
            $model->getAttribute($this->key) => $model->getAttribute($this->field),
        ];
    }
}
