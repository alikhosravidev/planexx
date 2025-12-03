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
            name: $request->string('name')->trim()->toString(),
            slug: $request->string('slug')->trim()->toString() ?: null,
            description: $request->string('description')->trim()->toString() ?: null,
            color: $request->string('color')->trim()->toString() ?: null,
            icon: $request->string('icon')->trim()->toString() ?: null,
            usageCount: $request->integer('usage_count', 0),
        );
    }

    public function fromRequestForUpdate(Request $request, Tag $tag): TagDTO
    {
        $name        = $request->string('name')->trim()->toString();
        $slug        = $request->string('slug')->trim()->toString();
        $description = $request->string('description')->trim()->toString();
        $color       = $request->string('color')->trim()->toString();
        $icon        = $request->string('icon')->trim()->toString();

        return new TagDTO(
            name: $name               !== '' ? $name : $tag->name,
            slug: $slug               !== '' ? $slug : $tag->slug,
            description: $description !== '' ? $description : $tag->description,
            color: $color             !== '' ? $color : $tag->color,
            icon: $icon               !== '' ? $icon : $tag->icon,
            usageCount: $request->integer('usage_count', $tag->usage_count),
        );
    }
}
