<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PWAAuthController extends BaseWebController
{
    public function login(): View
    {
        return view('pwa::pages.auth');
    }

    public function auth(Request $request): JsonResponse
    {
        $response = $this->apiPost('api.v1.client.user.auth', $request->all());

        if ($response['status'] === false) {
            return response()->json($response['message']);
        }

        return response()->json($response)
            ->cookie(
                'token',
                $response['result']['token'],
                60 * 24 * 30,
                '/',
                null,
                config('session.secure', false),
                false,
                false,
                'strict'
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
