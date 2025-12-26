<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Transformers\V1\Admin\CityTransformer;
use App\Core\Organization\Repositories\CityRepository;

class CityAdminController extends BaseAPIController
{
    public function __construct(
        CityRepository  $repository,
        CityTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
