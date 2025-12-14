<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers;

use App\Contracts\Controller\BaseWebController;
use App\Entities\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TagWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $pageTitle = 'مدیریت برچسب‌ها';
        $response  = $this->apiGet(
            'api.v1.admin.tags.index',
            [
                'sort' => '-usage_count',
            ],
        );

        return view('panel::tags.index', [
            'tags'       => $response['result']             ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
            'pageTitle'  => $pageTitle,
        ]);
    }

    public function show(Tag $tag): View
    {
        $response = $this->apiGet('api.v1.admin.tags.show', [
            'tag' => $tag->id,
        ]);

        return view('panel::tags.show', [
            'tag' => $response['result'] ?? [],
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
