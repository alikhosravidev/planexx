<?php

declare(strict_types=1);

namespace Modules\Product\Mappers;

use App\Utilities\OrNull;
use Illuminate\Http\Request;
use Modules\Product\DTOs\CategoryDTO;
use Modules\Product\Entities\Category;
use Modules\Product\ValueObjects\CategoryId;

class CategoryMapper
{
    public function fromRequest(Request $request): CategoryDTO
    {
        $parentIdValue = OrNull::intOrNull($request->input('parent_id'));

        return new CategoryDTO(
            name       : $request->input('name'),
            slug       : OrNull::stringOrNull($request->input('slug')),
            description: OrNull::stringOrNull($request->input('description')),
            parentId   : $parentIdValue !== null ? new CategoryId($parentIdValue) : null,
            iconClass  : OrNull::stringOrNull($request->input('icon_class')),
            sortOrder  : (int) ($request->input('sort_order') ?? 0),
            isActive   : $request->boolean('is_active', true),
        );
    }

    public function fromRequestForUpdate(Request $request, Category $category): CategoryDTO
    {
        $parentIdValue = $request->has('parent_id')
            ? OrNull::intOrNull($request->input('parent_id'))
            : $category->parent_id;

        return new CategoryDTO(
            name       : $request->input('name', $category->name),
            slug       : OrNull::stringOrNull($request->input('slug'))        ?? $category->slug,
            description: OrNull::stringOrNull($request->input('description')) ?? $category->description,
            parentId   : $parentIdValue !== null ? new CategoryId($parentIdValue) : null,
            iconClass  : OrNull::stringOrNull($request->input('icon_class')) ?? $category->icon_class,
            sortOrder  : (int) ($request->input('sort_order') ?? $category->sort_order),
            isActive   : $request->has('is_active')
                             ? $request->boolean('is_active')
                             : $category->is_active,
        );
    }
}
