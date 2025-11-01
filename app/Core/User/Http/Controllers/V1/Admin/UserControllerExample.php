<?php

declare(strict_types=1);

namespace App\Core\User\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\User\Repositories\UserRepository;
use App\Core\User\Transformers\UserTransformer;
use App\Services\ResponseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserControllerExample extends BaseController
{
    public function __construct(
        UserRepository  $repository,
        UserTransformer $transformer,
        ResponseBuilder $response,
    ) {
        parent::__construct($repository, $transformer, $response);
        
        $this->defaultPerPage = 20;
        $this->maxPerPage = 50;
        $this->defaultSortField = 'id';
        $this->defaultSortDirection = 'desc';
    }

    protected function beforeIndex(Request $request): void
    {
        logger()->info('User list requested', [
            'user_id' => auth()->id(),
            'filters' => $request->query('filter'),
        ]);
    }

    protected function customizeQuery(Builder $query, Request $request): Builder
    {
        if (!$request->has('show_inactive')) {
            $query->where('status', 'active');
        }

        if (!auth()->user()->isAdmin()) {
            $query->where('created_by', auth()->id());
        }

        return $query;
    }

    protected function afterIndex($results, Request $request): void
    {
        // event(new UsersListViewed($results->count()));
    }

    protected function authorizeShow($resource): void
    {
        if (!auth()->user()->isAdmin() && $resource->created_by !== auth()->id()) {
            throw new \Exception('Unauthorized access', 403);
        }
    }

    protected function beforeShow(int|string $id, Request $request): void
    {
        logger()->info('User profile viewed', [
            'user_id' => $id,
            'viewer_id' => auth()->id(),
        ]);
    }

    protected function afterShow($resource, Request $request): void
    {
        // $resource->increment('view_count');
    }
}
