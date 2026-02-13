<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Product;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Product\Entities\CustomList;

class PanelCustomListController extends BaseWebController
{
    public function index(Request $request): View
    {
        $queryParams = $request->except('filter');

        $response = $this->apiGet('api.v1.admin.product.custom-lists.index', $queryParams);

        return view('panel::products.custom-lists.index', [
            'lists' => $response['result'] ?? [],
        ]);
    }

    public function show(CustomList $customList, Request $request): View
    {
        $listResponse = $this->apiGet('api.v1.admin.product.custom-lists.show', [
            'custom_list' => $customList->id,
        ]);

        $queryParams                = $request->except('filter');
        $queryParams['custom_list'] = $customList->id;

        $itemsResponse = $this->apiGet('api.v1.admin.product.custom-lists.items.index', $queryParams);

        return view('panel::products.custom-lists.show', [
            'list'       => $listResponse['result']              ?? [],
            'items'      => $itemsResponse['result']             ?? [],
            'pagination' => $itemsResponse['meta']['pagination'] ?? [],
        ]);
    }
}
