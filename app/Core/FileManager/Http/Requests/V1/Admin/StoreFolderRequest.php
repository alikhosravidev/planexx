<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;

class StoreFolderRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'module_name' => 'sometimes|nullable|string|max:100',
            'parent_id'   => 'nullable|integer|exists:core_file_folders,id',
            'is_public'   => 'boolean',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'order'       => 'integer|min:0',
        ];
    }
}
