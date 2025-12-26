<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Transformers\V1\Client;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Http\Transformers\V1\Admin\FolderTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Http\Transformers\V1\Admin\TagTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class FileTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'folder',
        'uploader',
        'tags',
    ];

    protected array $fieldTransformers = [
        'file_type'  => EnumTransformer::class,
        'collection' => EnumTransformer::class,
    ];

    protected array $hidden = [];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'file_type_label'  => fn (File $file) => $file->file_type->label(),
            'collection_label' => fn (File $file) => $file->collection?->label(),
            'file_size_human'  => fn (File $file) => $this->formatBytes($file->file_size),
            // TODO: Fix N+1
            'is_favorite' => fn (File $file) => $file->favorites()->where('user_id', auth('sanctum')->id() ?? auth('web')->id())->exists(),
        ];
    }

    public function includeFolder(File $file)
    {
        return $this->itemRelation(
            model: $file,
            relationName: 'folder',
            transformer:FolderTransformer::class,
            foreignKey: 'folder_id',
        );
    }

    public function includeUploader(File $file)
    {
        return $this->itemRelation(
            model: $file,
            relationName: 'uploader',
            transformer: UserTransformer::class,
            foreignKey: 'uploaded_by',
        );
    }

    public function includeTags(File $file)
    {
        return $this->collectionRelation(
            model: $file,
            relationName: 'tags',
            transformer:TagTransformer::class,
        );
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
