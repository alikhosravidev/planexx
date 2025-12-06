<?php

declare(strict_types=1);

namespace App\Core\FileManager\Services;

use App\Core\FileManager\DTOs\FileUpdateDTO;
use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Repositories\FileRepository;
use Illuminate\Support\Facades\DB;

readonly class FileService
{
    public function __construct(
        private FileRepository       $fileRepository,
        private FileStorageService   $storageService,
    ) {
    }

    public function upload(FileUploadDTO $dto): File
    {
        return DB::transaction(function () use ($dto) {
            $fileData = $this->storageService->store($dto);

            return $this->fileRepository->create([
                ...$fileData,
                'title'        => $dto->title,
                'folder_id'    => $dto->folderId,
                'module_name'  => $dto->moduleName,
                'entity_type'  => $dto->entityType,
                'entity_id'    => $dto->entityId,
                'collection'   => $dto->collection?->value,
                'is_public'    => $dto->isPublic,
                'is_temporary' => $dto->isTemporary,
                'expires_at'   => $dto->expiresAt,
                'uploaded_by'  => $dto->uploadedBy,
            ]);
        });
    }

    public function update(File $file, FileUpdateDTO $dto): File
    {
        $data = $dto->toArray();

        if (empty($data)) {
            return $file;
        }

        return $this->fileRepository->update($file->id, $data);
    }

    public function delete(File $file): bool
    {
        return DB::transaction(function () use ($file) {
            $this->storageService->delete($file->file_path);

            return $this->fileRepository->delete($file->id);
        });
    }

    public function move(File $file, ?int $newFolderId): File
    {
        return $this->fileRepository->update($file->id, [
            'folder_id' => $newFolderId,
        ]);
    }

    public function incrementDownloadCount(File $file): void
    {
        $file->increment('download_count');
        $file->update(['last_accessed_at' => now()]);
    }

    public function incrementViewCount(File $file): void
    {
        $file->increment('view_count');
        $file->update(['last_accessed_at' => now()]);
    }

    public function getDownloadUrl(File $file): string
    {
        if ($file->is_public && $file->file_url) {
            return $file->file_url;
        }

        return $this->storageService->temporaryUrl($file->file_path, 60);
    }

    public function cleanupTemporary(): int
    {
        return DB::transaction(function () {
            $temporaryFiles = $this->fileRepository->query()
                ->where('is_temporary', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '<', now());
                })
                ->get();

            $deletedCount = 0;

            foreach ($temporaryFiles as $file) {
                $this->storageService->delete($file->file_path);
                $this->fileRepository->delete($file->id);
                $deletedCount++;
            }

            return $deletedCount;
        });
    }
}
