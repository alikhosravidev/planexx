<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Services\FileService;
use App\Core\Organization\Contracts\DepartmentServiceInterface;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Enums\DepartmentTypeEnum;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Domains\Department\DepartmentDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

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

            // Validate type-specific requirements
            $this->validateTypeRequirements($dto->type, $image, $dto->color, $dto->icon);

            $department = $this->departmentRepository->create($data);

            // Handle image upload for holding/brand types
            if ($dto->type->hasImage() && $image) {
                $file                  = $this->uploadDepartmentImage($department, $image);
                $department->image_url = $file->file_url;
                $department->save();
            }

            return $department;
        });
    }

    public function update(Department $department, DepartmentDTO $dto, ?UploadedFile $image = null): Department
    {
        return DB::transaction(function () use ($department, $dto, $image) {
            $data = $dto->toArray();

            // Validate type-specific requirements
            $this->validateTypeRequirements($dto->type, $image, $dto->color, $dto->icon, $department);

            // Handle image upload for holding/brand types
            if ($dto->type->hasImage()) {
                // Delete existing image if any and new image provided
                if ($department->image && $image) {
                    $this->fileService->delete($department->image);
                }

                if ($image) {
                    $file              = $this->uploadDepartmentImage($department, $image);
                    $data['image_url'] = $file->file_url;
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

    private function validateTypeRequirements(
        DepartmentTypeEnum $type,
        ?UploadedFile $image,
        ?string $color,
        ?string $icon,
        ?Department $existingDepartment = null
    ): void {
        if ($type->hasImage()) {
            // For holding/brand types, check if new image provided OR existing image exists
            if (!$image && (!$existingDepartment || !$existingDepartment->image)) {
                throw new InvalidArgumentException('Image is required for ' . $type->label());
            }
        }

        if ($type->hasIconAndColor() && (!$color || !$icon)) {
            throw new InvalidArgumentException('Icon and color are required for ' . $type->label());
        }
    }

    private function uploadDepartmentImage(Department $department, UploadedFile $image): File
    {
        $uploadDto = new FileUploadDTO(
            file      : $image,
            moduleName: $department->getModuleName(),
            title     : $department->name . ' Image',
            entityType: $department->getMorphClass(),
            entityId  : $department->id,
            isPublic  : true,
            uploadedBy: auth()->id(),
        );

        return $this->fileService->upload($uploadDto);
    }
}
