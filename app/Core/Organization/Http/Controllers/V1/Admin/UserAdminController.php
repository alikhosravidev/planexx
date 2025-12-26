<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Exceptions\UserException;
use App\Core\Organization\Http\Requests\V1\Admin\StoreUserRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateUserRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Core\Organization\Mappers\UserMapper;
use App\Core\Organization\Repositories\UserRepository;
use App\Core\Organization\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAdminController extends BaseAPIController
{
    public function __construct(
        UserRepository               $repository,
        UserTransformer              $transformer,
        private readonly UserService $service,
        private readonly UserMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto   = $this->mapper->fromRequest($request);
        $image = $request->file('image');

        $user = $this->service->create($dto, $image);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($user),
                ['redirect_url' => route('web.org.users.edit', $user->id)]
            ),
            'کاربر با موفقیت ایجاد شد.'
        );
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user  = $this->repository->findOrFail($id);
        $dto   = $this->mapper->fromRequestForUpdate($request, $user);
        $image = $request->file('image');

        $updated = $this->service->update($user, $dto, $image);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'کاربر با موفقیت بروزرسانی شد.'
        );
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        $user = $this->repository->findOrFail($id);

        if ($request->user()->id === $user->id) {
            throw UserException::selfDeletionPrevention();
        }

        $this->service->delete($user);

        return $this->response->success([], 'کاربر مورد نظر حذف شد.');
    }
}
