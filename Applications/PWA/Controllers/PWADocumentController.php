<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PWADocumentController extends BaseWebController
{
    public function index(Request $request): View
    {
        $filters = [];

        if ($request->filled('type') && $request->get('type') !== 'all') {
            $filters['category'] = $request->get('type');
        }

        $queryParams = [
            'filter'   => $filters,
            'sort'     => '-created_at',
            'per_page' => $request->get('per_page', 50),
            'includes' => 'folder,uploader',
        ];

        $filesResponse = $this->apiGet('api.v1.client.files.index', $queryParams);

        $files  = $filesResponse['result'] ?? [];
        $counts = $this->calculateCounts($files);

        return view('pwa::pages.documents', [
            'documents'    => $files,
            'pagination'   => $filesResponse['meta']['pagination'] ?? [],
            'counts'       => $counts,
            'activeFilter' => $request->get('type', 'all'),
        ]);
    }

    public function download(int $id): RedirectResponse
    {
        $response = $this->apiGet('api.v1.client.files.show', ['file' => $id]);
        $disk     = $response['result']['disk'] ?? 'public';
        $url      = $response['result']['url']  ?? Storage::disk($disk)->url($response['result']['file_path']);

        return response()->redirectTo($url);
    }

    private function calculateCounts(array $files): array
    {
        $counts = [
            'all'          => count($files),
            'contract'     => 0,
            'report'       => 0,
            'payroll'      => 0,
            'document'     => 0,
            'presentation' => 0,
        ];

        foreach ($files as $file) {
            $category = $file['category'] ?? 'document';

            if (isset($counts[$category])) {
                $counts[$category]++;
            }
        }

        return $counts;
    }
}
