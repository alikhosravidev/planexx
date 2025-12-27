<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PWADashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager        $statManager,
        private readonly QuickAccessManager $quickAccessManager,
    ) {
    }

    public function index(): View
    {
        $user = Auth::user();

        $userResponse = $this->apiGet('api.v1.client.org.users.show', [
            'user'      => $user->id,
            'includes'  => 'avatar,departments,primaryRoles',
            'withCount' => 'files,tasks',
        ]);

        $tasksResponse = $this->apiGet('api.v1.client.bpms.tasks.index', [
            'includes' => 'currentState,workflow',
            'per_page' => 10,
            'sort'     => '-priority,-created_at',
            'filter'   => [
                'assignee_id' => $user->id,
            ],
        ]);

        $documentsResponse = $this->apiGet('api.v1.client.files.index', [
            'per_page' => 5,
            'sort'     => '-created_at',
        ]);

        return view('pwa::pages.index', [
            'user'               => $userResponse['result']              ?? [],
            'tasks'              => $tasksResponse['result']             ?? [],
            'tasksPagination'    => $tasksResponse['meta']['pagination'] ?? [],
            'documents'          => $documentsResponse['result']         ?? [],
            'notificationsCount' => 0,
        ]);
    }
}
