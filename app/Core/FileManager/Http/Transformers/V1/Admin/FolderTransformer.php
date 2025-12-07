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

    public function includeParent(Folder $folder)
    {
        return $this->itemRelation(
            model: $folder,
            relationName: 'parent',
            transformer: $this,
        );
    }

    public function includeChildren(Folder $folder)
    {
        return $this->collectionRelation(
            model: $folder,
            relationName: 'children',
            transformer: $this,
        );
    }

    public function includeFiles(Folder $folder)
    {
        return $this->collectionRelation(
            model: $folder,
            relationName: 'files',
            transformer: FileTransformer::class,
        );
    }

    public function includeTags(Folder $folder)
    {
        return $this->collectionRelation(
            model: $folder,
            relationName: 'tags',
            transformer: TagTransformer::class,
        );
    }
}
