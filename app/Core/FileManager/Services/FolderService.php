<?php

declare(strict_types=1);

namespace App\Core\FileManager\Services;

use App\Core\FileManager\DTOs\FolderDTO;
use App\Core\FileManager\Entities\Folder;
use App\Core\FileManager\Repositories\FileRepository;
use App\Core\FileManager\Repositories\FolderRepository;

readonly class FolderService
{
    public function __construct(
        private FolderRepository $folderRepository,
        private FileRepository   $fileRepository,
    ) {
    }

    public function create(FolderDTO $dto): Folder
    {
        return $this->folderRepository->create($dto->toArray());
    }

    public function update(Folder $folder, FolderDTO $dto): Folder
    {
        return $this->folderRepository->update($folder->id, $dto->toArray());
    }

    public function delete(Folder $folder): bool
    {
        $archiveFolder = $this->folderRepository
            ->newQuery()
            ->where('name', 'آرشیو')
            ->first();
        $this->fileRepository
            ->newQuery()
            ->where('folder_id', $folder->id)
            ->update(['folder_id' => $archiveFolder->id]);

        return $this->folderRepository->delete($folder->id);
    }

    public function move(Folder $folder, ?int $newParentId): Folder
    {
        return $this->folderRepository->update($folder->id, [
            'parent_id' => $newParentId,
        ]);
    }
}
