<?php

declare(strict_types=1);

namespace App\Core\User\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\User\Repositories\CityRepository;
use App\Core\User\Transformers\CityTransformer;
use App\Services\ResponseBuilder;

class CityController extends BaseController
{
    public function __construct(
        CityRepository  $repository,
        CityTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
