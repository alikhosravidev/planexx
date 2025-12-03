<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;

class UpdateFileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => 'nullable|string|max:255',
            'folder_id' => 'nullable|integer|exists:core_file_folders,id',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
