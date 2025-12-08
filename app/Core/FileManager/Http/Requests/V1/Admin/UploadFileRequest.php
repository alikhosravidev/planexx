<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\FileManager\Enums\FileCollectionEnum;
use Illuminate\Validation\Rule;

class UploadFileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'         => 'required|file|max:102400',
            'title'        => 'nullable|string|max:255',
            'folder_id'    => 'nullable|integer|exists:core_file_folders,id',
            'module_name'  => 'nullable|string|max:100',
            'entity_type'  => 'nullable|string|max:100',
            'entity_id'    => 'nullable|integer',
            'collection'   => ['nullable', 'integer', Rule::enum(FileCollectionEnum::class)],
            'is_public'    => 'boolean',
            'is_temporary' => 'boolean',
            'expires_at'   => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'فایل نباید بزرگتر از 100 مگابایت باشد.',
        ];
    }
}
