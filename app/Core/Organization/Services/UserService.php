<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Core\FileManager\Services\FileService;
use App\Core\Organization\Entities\User;
use App\Core\Organization\events\UserCreated;
use App\Core\Organization\events\UserUpdated;
use App\Core\Organization\Repositories\UserRepository;
use App\Domains\User\UserUpsertDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private FileService $fileService,
    ) {
    }

    public function create(UserUpsertDTO $dto, ?UploadedFile $image = null): User
    {
        /** @var User $user */
        $user = $this->userRepository->create($dto->toArray());

        if ($dto->departmentId) {
            $user->departments()->sync([$dto->departmentId => ['is_primary' => true]]);
        }

        if ($dto->password) {
            $user->changePassword($dto->password)->save();
        }

        if ($image) {
            $this->uploadDepartmentImage($user, $image);
        }

        event(new UserCreated($user));

        return $user;
    }

    public function update(User $user, UserUpsertDTO $dto, ?UploadedFile $image = null): User
    {
        return DB::transaction(function () use ($user, $dto, $image) {
            /** @var User $updated */
            $updated = $this->userRepository->update($user->id, $dto->toArray());

            if ($dto->departmentId !== null) {
                $updated->departments()->sync([$dto->departmentId => ['is_primary' => true]]);
            }

            if ($dto->password) {
                $updated->changePassword($dto->password)->save();
            }

            if ($updated->avatar && $image) {
                $this->fileService->delete($updated->avatar);
            }

            if ($image) {
                $this->uploadDepartmentImage($updated, $image);
            }

            event(new UserUpdated($updated));

            return $updated;
        });
    }

    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user->id);
    }

    // TODO: handle this action using events.
    private function uploadDepartmentImage(User $user, UploadedFile $image): File
    {
        $uploadDto = new FileUploadDTO(
            file      : $image,
            moduleName: $user->getModuleName(),
            title     : $user->full_name . ' Image',
            entityType: $user->getMorphClass(),
            entityId  : $user->id,
            collection: FileCollectionEnum::AVATAR,
            isPublic  : true,
            uploadedBy: auth()->id(),
        );

        return $this->fileService->upload($uploadDto);
    }
}
