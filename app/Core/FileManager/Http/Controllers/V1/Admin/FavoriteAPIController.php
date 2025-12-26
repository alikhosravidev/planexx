<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\FileManager\Repositories\FileRepository;
use App\Core\FileManager\Repositories\FolderRepository;
use App\Http\Transformers\V1\Admin\FavoriteTransformer;
use App\Repositories\FavoriteRepository;
use App\Services\Favorite\FavoriteService;
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

        $isFavorite = $this->service->for($file)->by($userId)->toggle();

        return $this->response->success(
            ['is_favorite' => $isFavorite],
            $isFavorite ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد'
        );
    }

    public function toggleFolder(Request $request, int $folderId): JsonResponse
    {
        $folder = $this->folderRepository->findOrFail($folderId);
        $userId = $request->user()->id;

        $isFavorite = $this->service->for($folder)->by($userId)->toggle();

        return $this->response->success(
            ['is_favorite' => $isFavorite],
            $isFavorite ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد'
        );
    }
}
