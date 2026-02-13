<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Product;

use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PanelCategoryController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
    ) {
    }

    public function index(Request $request): View
    {
        $queryParams           = $request->except(['filter', 'parent_filter']);
        $queryParams['filter'] = [];

        $queryParams['includes'] = 'parent';
        $queryParams['per_page'] = 100;

        if ($request->has('parent_filter')) {
            $filter = $request->query('parent_filter');

            if ($filter === 'root') {
                $queryParams['filter']['parent_id'] = null;
            } else {
                $queryParams['filter']['parent_id']['not_null'] = true;
            }
        }

        $response = $this->apiGet('api.v1.admin.product.categories.index', $queryParams);

        return view('panel::products.categories', [
            'categories' => $response['result']             ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
            'stats'      => $this->statManager->getTransformed('product.category.stats'),
        ]);
    }
}
