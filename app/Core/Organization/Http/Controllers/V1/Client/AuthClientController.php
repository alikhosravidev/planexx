<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Client;

use App\Core\Organization\Http\Controllers\V1\BaseAuthController;
use App\Core\Organization\Http\Transformers\V1\Client\AuthTransformer;
use App\Core\Organization\Repositories\UserRepository;
use App\Core\Organization\Services\Auth\AuthService;
use App\Services\HttpRequestService;

class AuthClientController extends BaseAuthController
{
    public function __construct(
        UserRepository $userRepository,
        AuthTransformer $authTransformer,
        AuthService $authService,
        HttpRequestService $requestService,
    ) {
        parent::__construct($authService, $requestService, $userRepository, $authTransformer);
    }
}
