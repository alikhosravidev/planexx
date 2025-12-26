<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Organization;

use Applications\Contracts\BaseWebController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelAuthController extends BaseWebController
{
    public function login(): View
    {
        return view('panel::auth.index');
    }

    public function auth(Request $request): JsonResponse
    {
        $response = $this->apiPost('api.v1.admin.user.auth', $request->all());

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

    public function logout(Request $request): JsonResponse
    {
        $response = $this->apiPost('api.v1.admin.user.logout');

        return response()->json($response)->withoutCookie('token');
    }
}
