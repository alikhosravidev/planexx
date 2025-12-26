<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Client;

use App\Core\Organization\Http\Controllers\V1\Admin\AuthAdminController as AdminAuthAPIController;
use App\Core\Organization\Http\Transformers\V1\Client\AuthTransformer;
use App\Core\Organization\Http\Transformers\V1\Client\UserTransformer;
use App\Core\Organization\Services\Auth\AuthService;
use App\Services\HttpRequestService;

class AuthClientController extends AdminAuthAPIController
{
    public function __construct(
        UserTransformer $userRepository,
        AuthTransformer $authTransformer,
        AuthService $authService,
        HttpRequestService $requestService,
    ) {
        parent::__construct($authService, $requestService, $userRepository, $authTransformer);
    }
}
