<?php

declare(strict_types=1);

namespace App\Core\FileManager\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\FileManager\Entities\Folder;

class FolderRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'          => '=',
        'name'        => 'like',
        'module_name' => '=',
        'parent_id'   => '=',
        'is_public'   => '=',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'order',
        'created_at',
        'updated_at',
    ];

    protected array $defaultWith = [];

    protected array $defaultWithCount = [
        'files',
        'children',
    ];

    public function model(): string
    {
        return Folder::class;
    }
}
