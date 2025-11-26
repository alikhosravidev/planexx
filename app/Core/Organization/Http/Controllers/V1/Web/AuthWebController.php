<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\View\View;

class AuthWebController extends BaseWebController
{
    /**
     * Display the login page
     *
     * The authentication form will use Axios to directly call the API endpoints
     * for initiateAuth and auth operations
     */
    public function login(): View
    {
        return view('Organization::auth');
    }
}
