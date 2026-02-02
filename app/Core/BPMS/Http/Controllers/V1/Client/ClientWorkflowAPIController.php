<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\V1\Client;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Http\Transformers\V1\Admin\WorkflowTransformer;
use App\Core\BPMS\Repositories\WorkflowRepository;

class ClientWorkflowAPIController extends BaseAPIController
{
    public function __construct(
        WorkflowRepository  $repository,
        WorkflowTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
