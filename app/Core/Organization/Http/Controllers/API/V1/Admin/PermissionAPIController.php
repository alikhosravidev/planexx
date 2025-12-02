<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Transformers\V1\Admin\PermissionTransformer;
use App\Core\Organization\Repositories\PermissionRepository;

class PermissionAPIController extends BaseAPIController
{
    public function __construct(
        PermissionRepository $repository,
        PermissionTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
