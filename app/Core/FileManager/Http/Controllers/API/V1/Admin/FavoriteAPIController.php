<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\FileManager\Http\Transformers\V1\Admin\FavoriteTransformer;
use App\Core\FileManager\Repositories\FavoriteRepository;
use App\Core\FileManager\Repositories\FileRepository;
use App\Core\FileManager\Repositories\FolderRepository;
use App\Core\FileManager\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteAPIController extends BaseAPIController
{
    public function __construct(
        FavoriteRepository                 $repository,
        FavoriteTransformer                $transformer,
        private readonly FavoriteService   $service,
        private readonly FileRepository    $fileRepository,
        private readonly FolderRepository  $folderRepository,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function toggleFile(Request $request, int $fileId): JsonResponse
    {
        $file   = $this->fileRepository->findOrFail($fileId);
        $userId = $request->user()->id;

        $isFavorite = $this->service->toggle($userId, $file);

        return $this->response->success([
            'is_favorite' => $isFavorite,
            'message'     => $isFavorite ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    public function toggleFolder(Request $request, int $folderId): JsonResponse
    {
        $folder = $this->folderRepository->findOrFail($folderId);
        $userId = $request->user()->id;

        $isFavorite = $this->service->toggle($userId, $folder);

        return $this->response->success([
            'is_favorite' => $isFavorite,
            'message'     => $isFavorite ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $favorites = $this->repository
            ->findWhere(['user_id' => $userId])
            ->load('entity');

        return $this->response->success([
            'data' => $favorites,
        ]);
    }
}
