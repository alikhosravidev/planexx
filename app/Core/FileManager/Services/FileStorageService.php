<?php

declare(strict_types=1);

namespace App\Core\FileManager\Services;

use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Enums\FileTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

readonly class FileStorageService
{
    public function __construct(
        private string $disk = 's3',
    ) {
    }

    public function store(FileUploadDTO $dto): array
    {
        $file = $dto->file;

        $fileName = $this->generateFileName($file);
        $filePath = $this->generateFilePath($dto->moduleName, $fileName);
        $fileHash = $this->generateFileHash($file);
        $fileType = $this->detectFileType($file);
        $metadata = $this->extractMetadata($file, $fileType);

        $storedPath = Storage::disk($this->disk)->putFileAs(
            dirname($filePath),
            $file,
            basename($filePath),
            'private'
        );

        $fileUrl = $dto->isPublic
            ? Storage::disk($this->disk)->url($storedPath)
            : null;

        return [
            'original_name' => $file->getClientOriginalName(),
            'file_name'     => $fileName,
            'file_path'     => $storedPath,
            'file_url'      => $fileUrl,
            'disk'          => $this->disk,
            'mime_type'     => $file->getMimeType() ?? 'application/octet-stream',
            'extension'     => $file->getClientOriginalExtension(),
            'file_size'     => $file->getSize(),
            'file_hash'     => $fileHash,
            'file_type'     => $fileType->value,
            ...$metadata,
        ];
    }

    public function delete(string $filePath): bool
    {
        return Storage::disk($this->disk)->delete($filePath);
    }

    public function exists(string $filePath): bool
    {
        return Storage::disk($this->disk)->exists($filePath);
    }

    public function url(string $filePath): string
    {
        return Storage::disk($this->disk)->url($filePath);
    }

    public function temporaryUrl(string $filePath, int $minutes = 60): string
    {
        return Storage::disk($this->disk)->temporaryUrl($filePath, now()->addMinutes($minutes));
    }

    public function move(string $oldPath, string $newPath): bool
    {
        return Storage::disk($this->disk)->move($oldPath, $newPath);
    }

    private function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $uuid      = Str::uuid()->toString();

        return $extension ? "{$uuid}.{$extension}" : $uuid;
    }

    private function generateFilePath(?string $moduleName, string $fileName): string
    {
        $basePath = $moduleName ? "modules/{$moduleName}" : 'general';
        $datePath = now()->format('Y/m');

        return "{$basePath}/{$datePath}/{$fileName}";
    }

    private function generateFileHash(UploadedFile $file): string
    {
        return hash_file('sha256', $file->getRealPath());
    }

    private function detectFileType(UploadedFile $file): FileTypeEnum
    {
        $mimeType = $file->getMimeType();

        return match (true) {
            str_starts_with($mimeType, 'image/') => FileTypeEnum::IMAGE,
            str_starts_with($mimeType, 'video/') => FileTypeEnum::VIDEO,
            str_starts_with($mimeType, 'audio/') => FileTypeEnum::AUDIO,
            in_array($mimeType, [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ]) => FileTypeEnum::DOCUMENT,
            in_array($mimeType, [
                'application/zip',
                'application/x-rar-compressed',
                'application/x-7z-compressed',
                'application/x-tar',
                'application/gzip',
            ])      => FileTypeEnum::ARCHIVE,
            default => FileTypeEnum::OTHER,
        };
    }

    private function extractMetadata(UploadedFile $file, FileTypeEnum $fileType): array
    {
        $metadata = [];

        if ($fileType === FileTypeEnum::IMAGE) {
            $imageInfo = @getimagesize($file->getRealPath());

            if ($imageInfo !== false) {
                $metadata['width']  = $imageInfo[0];
                $metadata['height'] = $imageInfo[1];

                if ($imageInfo[0] > 0 && $imageInfo[1] > 0) {
                    $metadata['aspect_ratio'] = round($imageInfo[0] / $imageInfo[1], 2);
                }
            }
        }

        return $metadata;
    }
}
