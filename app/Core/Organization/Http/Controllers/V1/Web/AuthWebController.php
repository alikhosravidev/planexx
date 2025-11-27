<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthWebController extends BaseWebController
{
    public function login(): View
    {
        return view('Organization::auth');
    }

    public function auth(Request $request): JsonResponse
    {
        $response = $this->forwardToApi('api.user.auth', $request->all(), 'POST');

        return response()->json($response)
            ->cookie(
                'token',
                $response['result']['token'],
                60 * 24 * 30,
                '/',
                null,
                config('session.secure', false),
                true,
                false,
                'strict'
            );
    }

    public function logout(Request $request): JsonResponse
    {
        $response = $this->forwardToApi('api.user.logout', [], 'POST');

        return response()->json($response)->withoutCookie('token');
    }
}
