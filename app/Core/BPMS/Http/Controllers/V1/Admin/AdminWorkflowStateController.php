<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\BPMS\Http\Transformers\V1\Admin\WorkflowStateTransformer;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminWorkflowStateController extends BaseAPIController
{
    public function __construct(
        WorkflowStateRepository $repository,
        WorkflowStateTransformer $transformer
    ) {
        parent::__construct($repository, $transformer);
    }

    protected function customizeQuery(Builder $query, Request $request): Builder
    {
        return $query->where('workflow_id', '=', $request->route()->parameter('workflow_id'));
    }
}
