<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

final readonly class HttpRequestService
{
    public function __construct(
        private Request $request,
        private Router  $router
    ) {
    }

    public function isWebRequest(): bool
    {
        $webMiddlewares     = ['web', 'telescope'];
        $currentMiddlewares = $this->router->current()?->gatherMiddleware() ?? [];

        return ! empty(array_intersect($webMiddlewares, $currentMiddlewares));
    }

    public function getTokenFromRequest(): ?string
    {
        if ($this->isWebRequest()) {
            return $this->request->cookie('token');
        }

        return $this->request->bearerToken();
    }
}
