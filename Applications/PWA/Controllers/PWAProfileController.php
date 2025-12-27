<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PWAProfileController extends BaseWebController
{
    public function index(): View
    {
        $user = Auth::user();

        $response = $this->apiGet('api.v1.client.org.users.show', [
            'user'     => $user->id,
            'includes' => 'avatar,departments,primaryRoles,jobPosition,directManager',
        ]);

        return view('pwa::pages.profile', [
            'user' => $response['result'] ?? [],
        ]);
    }
}
