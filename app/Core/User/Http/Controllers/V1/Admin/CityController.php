<?php

declare(strict_types=1);

namespace App\Core\User\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\User\Http\Transformers\V1\Admin\CityTransformer;
use App\Core\User\Repositories\CityRepository;

class CityController extends BaseController
{
    public function __construct(
        CityRepository  $repository,
        CityTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
