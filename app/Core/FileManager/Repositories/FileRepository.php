<?php

declare(strict_types=1);

namespace App\Core\FileManager\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\FileManager\Entities\File;

class FileRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'            => '=',
        'uuid'          => '=',
        'original_name' => 'like',
        'file_name'     => 'like',
        'title'         => 'like',
        'mime_type'     => '=',
        'extension'     => '=',
        'file_type'     => '=',
        'collection'    => '=',
        'folder_id'     => '=',
        'module_name'   => '=',
        'uploaded_by'   => '=',
        'entity_type'   => '=',
        'entity_id'     => '=',
        'is_public'     => '=',
        'is_temporary'  => '=',
        'is_active'     => '=',
    ];

    public array $sortableFields = [
        'id',
        'original_name',
        'file_name',
        'file_size',
        'download_count',
        'view_count',
        'last_accessed_at',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return File::class;
    }
}
