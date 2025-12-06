<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\FileManager\Entities\Folder;
use App\Http\Transformers\V1\Admin\TagTransformer;

class FolderTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'parent',
        'children',
        'files',
        'tags',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'files_count'    => fn (Folder $folder) => $folder->files_count ?? 0,
            'children_count' => fn (Folder $folder) => $folder->children_count ?? 0,
        ];
    }

    public function includeParent(Folder $folder): ?array
    {
        if (!$folder->parent) {
            return null;
        }

        return $this->item($folder->parent, new self());
    }

    public function includeChildren(Folder $folder): array
    {
        return $this->collection($folder->children, new self());
    }

    public function includeFiles(Folder $folder): array
    {
        return $this->collection($folder->files, new FileTransformer());
    }

    public function includeTags(Folder $folder): array
    {
        return $this->collection($folder->tags, new TagTransformer());
    }
}
