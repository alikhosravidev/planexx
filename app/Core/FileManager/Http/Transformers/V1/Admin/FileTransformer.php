<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\FileManager\Entities\File;

class FileTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'folder',
        'uploader',
        'tags',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'file_type_label'  => fn (File $file) => $file->file_type->label(),
            'collection_label' => fn (File $file) => $file->collection?->label(),
            'file_size_human'  => fn (File $file) => $this->formatBytes($file->file_size),
        ];
    }

    public function includeFolder(File $file): ?array
    {
        if (!$file->folder) {
            return null;
        }

        return $this->item($file->folder, new FolderTransformer());
    }

    public function includeUploader(File $file): ?array
    {
        if (!$file->uploader) {
            return null;
        }

        return $this->item($file->uploader, new UserTransformer());
    }

    public function includeTags(File $file): array
    {
        return $this->collection($file->tags, new TagTransformer());
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
