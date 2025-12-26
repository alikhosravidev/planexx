<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PWAAuthController extends BaseWebController
{
    public function login(): View
    {
        return view('pwa::auth.index');
    }

    public function auth(Request $request): RedirectResponse
    {
        $this->apiPost(
            'api.v1.client.user.auth',
            $request->only(['email', 'password', 'remember']),
        );

        return redirect()->intended(
            route('pwa.dashboard')
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->apiPost('api.v1.client.user.logout');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pwa.login');
    }
}
