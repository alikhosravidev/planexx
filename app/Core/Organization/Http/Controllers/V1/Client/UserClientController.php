<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Client;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Transformers\V1\Client\UserTransformer;
use App\Core\Organization\Repositories\UserRepository;

class UserClientController extends BaseAPIController
{
    public function __construct(
        UserRepository  $repository,
        UserTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
