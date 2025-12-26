<?php

declare(strict_types=1);

namespace App\Middlewares;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            $host = $request->getHost();

            if (str_contains($host, config('app.domains.pwa'))) {
                return route('pwa.login');
            }

            if (str_contains($host, config('app.domains.admin_panel'))) {
                return route('login');
            }

            return route('login');
        }

        return null;
    }

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
