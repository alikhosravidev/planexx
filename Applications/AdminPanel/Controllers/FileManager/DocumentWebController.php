<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\FileManager;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $filters = [];

        if ($request->filled('file_type')) {
            $filters['file_type'] = $request->get('file_type');
        }

        if ($request->filled('folder_id')) {
            $filters['folder_id'] = $request->get('folder_id');
        }

        if ($request->filled('is_temporary')) {
            $filters['is_temporary'] = $request->boolean('is_temporary');
        }

        $queryParams             = $request->except('filter');
        $queryParams['filter']   = $filters;
        $queryParams['sort']     = $request->get('sort', '-created_at');
        $queryParams['per_page'] = $request->get('per_page', 20);
        $queryParams['includes'] = 'folder,uploader.avatar,tags';

        $filesResponse = $this->apiGet('api.v1.admin.file-manager.files.index', $queryParams);

        $foldersResponse = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'filter'    => ['parent_id' => null],
            'withCount' => 'files',
            'per_page'  => 100,
        ]);

        return view('panel::documents.index', [
            'files'       => $filesResponse['result']             ?? [],
            'folders'     => $foldersResponse['result']           ?? [],
            'pagination'  => $filesResponse['meta']['pagination'] ?? [],
            'currentPage' => 'documents-all',
        ]);
    }

    public function folder(Request $request, int $folderId): View
    {
        $folderResponse = $this->apiGet('api.v1.admin.file-manager.folders.show', [
            'folder' => $folderId,
            // Request only supported includes on FolderTransformer
            'includes' => 'parent,children',
        ]);

        $folder = $folderResponse['result'] ?? [];

        $filters = ['folder_id' => $folderId];

        if ($request->filled('search')) {
            $filters['search'] = $request->get('search');
        }

        $filesResponse = $this->apiGet('api.v1.admin.file-manager.files.index', [
            'filter'   => $filters,
            'sort'     => $request->get('sort', '-created_at'),
            'per_page' => $request->get('per_page', 20),
            'includes' => 'uploader.avatar,tags',
        ]);

        return view('panel::documents.folder', [
            'folder'      => $folder,
            'files'       => $filesResponse['result']             ?? [],
            'pagination'  => $filesResponse['meta']['pagination'] ?? [],
            'currentPage' => 'documents-all',
            'request'     => $request,
        ]);
    }

    public function favorites(Request $request): View
    {
        $queryParams             = $request->except('filter');
        $queryParams['per_page'] = $request->get('per_page', 20);
        $queryParams['filter']   = ['is_favorite' => 1];
        $favoritesResponse       = $this->apiGet('api.v1.admin.file-manager.files.index', $queryParams);
        $foldersResponse         = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'filter'   => ['parent_id' => null],
            'per_page' => 100,
        ]);

        return view('panel::documents.favorites', [
            'files'       => $favoritesResponse['result']             ?? [],
            'folders'     => $foldersResponse['result']               ?? [],
            'pagination'  => $favoritesResponse['meta']['pagination'] ?? [],
            'currentPage' => 'documents-favorites',
        ]);
    }

    public function recent(Request $request): View
    {
        $filesResponse = $this->apiGet('api.v1.admin.file-manager.files.index', [
            'sort'     => '-last_accessed_at',
            'per_page' => $request->get('per_page', 20),
            'includes' => 'folder,uploader,tags',
        ]);

        $foldersResponse = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'per_page' => 100,
        ]);

        return view('panel::documents.recent', [
            'files'       => $filesResponse['result']             ?? [],
            'folders'     => $foldersResponse['result']           ?? [],
            'pagination'  => $filesResponse['meta']['pagination'] ?? [],
            'currentPage' => 'documents-recent',
            'request'     => $request,
        ]);
    }

    public function temporary(Request $request): View
    {
        $filesResponse = $this->apiGet('api.v1.admin.file-manager.files.index', [
            'filter'   => ['is_temporary' => true],
            'sort'     => '-created_at',
            'per_page' => $request->get('per_page', 20),
            'includes' => 'folder,uploader,tags',
        ]);

        $foldersResponse = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'per_page' => 100,
        ]);

        $stats = [
            'total_files' => $filesResponse['meta']['pagination']['total'] ?? 0,
            'total_size'  => '0 MB',
        ];

        return view('panel::documents.temporary', [
            'files'       => $filesResponse['result']             ?? [],
            'folders'     => $foldersResponse['result']           ?? [],
            'pagination'  => $filesResponse['meta']['pagination'] ?? [],
            'stats'       => $stats,
            'currentPage' => 'documents-temporary',
        ]);
    }

    public function createFolder(): View
    {
        $foldersResponse = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'per_page' => 100,
        ]);

        return view('panel::documents.create-folder', [
            'folders' => $foldersResponse['result'] ?? [],
        ]);
    }

    public function editFolder(int $folderId): View
    {
        $folderResponse = $this->apiGet('api.v1.admin.file-manager.folders.show', [
            'folder'   => $folderId,
            'includes' => 'parent',
        ]);

        $foldersResponse = $this->apiGet('api.v1.admin.file-manager.folders.index', [
            'per_page' => 100,
        ]);

        return view('panel::documents.edit-folder', [
            'folder'  => $folderResponse['result']  ?? [],
            'folders' => $foldersResponse['result'] ?? [],
        ]);
    }

    public function download(int $id): RedirectResponse
    {
        $response = $this->apiGet('api.v1.admin.file-manager.files.show', ['file' => $id]);
        $disk     = $response['result']['disk'];
        $url      = $response['result']['url'] ?? Storage::disk($disk)->url($response['result']['file_path']);

        return response()->redirectTo($url);
    }
}
