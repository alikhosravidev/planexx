<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Core\FileManager\Services\FileService;
use App\Core\Organization\Contracts\DepartmentServiceInterface;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Domains\Department\DepartmentDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

readonly class DepartmentService implements DepartmentServiceInterface
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
        private FileService $fileService,
    ) {
    }

    public function create(DepartmentDTO $dto, ?UploadedFile $image = null): Department
    {
        return DB::transaction(function () use ($dto, $image) {
            $data = $dto->toArray();

            $department = $this->departmentRepository->create($data);

            if ($department->thumbnail && $image) {
                $this->fileService->delete($department->thumbnail);
            }

            if ($dto->type->hasImage() && $image) {
                $this->uploadDepartmentImage($department, $image);
            }

            return $department;
        });
    }

    public function update(Department $department, DepartmentDTO $dto, ?UploadedFile $image = null): Department
    {
        return DB::transaction(function () use ($department, $dto, $image) {
            $data = $dto->toArray();

            if ($dto->type->hasImage()) {
                if ($department->image && $image) {
                    $this->fileService->delete($department->image);
                }

                if ($image) {
                    $this->uploadDepartmentImage($department, $image);
                }
            }

            return $this->departmentRepository->update($department->id, $data);
        });
    }

    public function delete(Department $department): bool
    {
        return DB::transaction(function () use ($department) {
            // Delete associated image if exists
            if ($department->image) {
                $this->fileService->delete($department->image);
            }

            return $this->departmentRepository->delete($department->id);
        });
    }

    // TODO: handle this action using events.
    private function uploadDepartmentImage(Department $department, UploadedFile $image): File
    {
        $uploadDto = new FileUploadDTO(
            file      : $image,
            moduleName: $department->getModuleName(),
            title     : $department->name . ' Image',
            entityType: $department->getMorphClass(),
            entityId  : $department->id,
            collection: FileCollectionEnum::THUMBNAIL,
            isPublic  : true,
            uploadedBy: auth()->id()
        );

        return $this->fileService->upload($uploadDto);
    }
}
