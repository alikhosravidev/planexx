<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\V1\Client;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Http\Transformers\V1\Client\TaskTransformer;
use App\Core\BPMS\Repositories\TaskRepository;

class ClientTaskController extends BaseAPIController
{
    public function __construct(
        TaskRepository  $repository,
        TaskTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
