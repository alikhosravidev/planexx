<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\TagDTO;
use App\Entities\Tag;
use Illuminate\Http\Request;

class TagMapper
{
    public function fromRequest(Request $request): TagDTO
    {
        return new TagDTO(
            name: $request->input('name'),
            slug: $request->input('slug'),
            description: $request->input('description'),
            color: $request->input('color'),
            icon: $request->input('icon'),
            usageCount: $request->integer('usage_count', 0),
        );
    }

    public function fromRequestForUpdate(Request $request, Tag $tag): TagDTO
    {
        return new TagDTO(
            name: $request->input('name')               ?? $tag->name,
            slug: $request->input('slug')               ?? $tag->slug,
            description: $request->input('description') ?? $tag->description,
            color: $request->input('color')             ?? $tag->color,
            icon: $request->input('icon')               ?? $tag->icon,
            usageCount: $request->integer('usage_count', $tag->usage_count),
        );
    }
}
