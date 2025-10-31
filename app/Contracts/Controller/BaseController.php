<?php

declare(strict_types=1);

namespace App\Contracts\Controller;

use App\Contracts\Repository\BaseRepository;
use App\Contracts\Transformer\BaseTransformer;
use App\Services\ResponseBuilder;

abstract class BaseController
{
    public function __construct(
        protected readonly BaseRepository  $repository,
        protected readonly BaseTransformer $transformer,
        protected readonly ResponseBuilder $response,
    ) {
    }
}
