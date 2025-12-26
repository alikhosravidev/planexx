<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers;

use App\Entities\Tag;
use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PanelTagController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
    ) {
    }

    public function index(Request $request): View
    {
        $pageTitle           = 'مدیریت برچسب‌ها';
        $queryParams         = $request->except('filter');
        $queryParams['sort'] = '-usage_count';
        $response            = $this->apiGet('api.v1.admin.tags.index', $queryParams);

        return view('panel::tags.index', [
            'tags'       => $response['result']             ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
            'pageTitle'  => $pageTitle,
            // TODO: Refactor stats (get data from API)
            'stats' => $this->statManager->getTransformed('app.tag.stats'),
        ]);
    }

    public function create(): View
    {
        return view('panel::tags.add-or-edit');
    }

    public function edit(Tag $tag): View
    {
        $response = $this->apiGet('api.v1.admin.tags.show', [
            'tag' => $tag->id,
        ]);

        return view('panel::tags.add-or-edit', [
            'tag' => $response['result'] ?? [],
        ]);
    }
}
