<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Product;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;

class PanelProductController extends BaseWebController
{
    public function index(Request $request): View
    {
        $queryParams           = $request->except('filter');
        $filters               = [];
        $queryParams['filter'] = $filters;

        if ($request->filled('category_id')) {
            $filters['category_id'] = $request->get('category_id');
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->get('status');
        }

        $queryParams['filter']   = $filters;
        $queryParams['includes'] = 'categories';

        $response = $this->apiGet('api.v1.admin.product.products.index', $queryParams);

        return view('panel::products.index', [
            'products'   => $response['result']             ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
        ]);
    }

    public function show(Product $product): View
    {
        $response = $this->apiGet('api.v1.admin.product.products.show', [
            'product'  => $product->id,
            'includes' => 'categories',
        ]);

        return view('panel::products.show', [
            'product' => $response['result'] ?? [],
        ]);
    }

    public function create(): View
    {
        return view('panel::products.create');
    }

    public function edit(Product $product): View
    {
        $response = $this->apiGet('api.v1.admin.product.products.show', [
            'product'  => $product->id,
            'includes' => 'categories',
        ]);

        return view('panel::products.edit', [
            'product' => $response['result'] ?? [],
        ]);
    }
}
