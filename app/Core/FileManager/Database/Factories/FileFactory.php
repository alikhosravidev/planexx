<?php

declare(strict_types=1);

namespace App\Core\FileManager\Database\Factories;

use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Entities\Folder;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Core\FileManager\Enums\FileTypeEnum;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        $type       = $this->faker->randomElement(FileTypeEnum::cases());
        $collection = $this->faker->randomElement(FileCollectionEnum::cases());

        $originalName = $this->faker->lexify('file_????????') . '.' . $this->faker->fileExtension();
        $fileName     = Str::uuid() . '.' . $this->faker->fileExtension();

        return [
            'uuid'             => (string) Str::uuid(),
            'entity_type'      => null,
            'entity_id'        => null,
            'original_name'    => $originalName,
            'file_name'        => $fileName,
            'file_path'        => 'uploads/' . $fileName,
            'file_url'         => $this->faker->optional()->url(),
            'disk'             => 'local',
            'mime_type'        => $this->faker->mimeType(),
            'extension'        => pathinfo($fileName, PATHINFO_EXTENSION),
            'file_size'        => $this->faker->numberBetween(10_000, 5_000_000),
            'file_hash'        => $this->faker->optional()->sha256(),
            'file_type'        => $type,
            'collection'       => $collection,
            'width'            => $this->faker->optional()->numberBetween(100, 4000),
            'height'           => $this->faker->optional()->numberBetween(100, 4000),
            'aspect_ratio'     => $this->faker->optional()->randomElement(['1:1', '4:3', '16:9']),
            'duration'         => $this->faker->optional()->numberBetween(1, 3600),
            'resolution'       => $this->faker->optional()->randomElement(['720p', '1080p', '4K']),
            'frame_rate'       => $this->faker->optional()->randomFloat(2, 15, 120),
            'uploaded_by'      => User::factory(),
            'folder_id'        => Folder::factory(),
            'module_name'      => $this->faker->optional()->randomElement(['users', 'documents', 'forms', 'bpms']),
            'is_public'        => $this->faker->boolean(20),
            'download_count'   => $this->faker->numberBetween(0, 1000),
            'view_count'       => $this->faker->numberBetween(0, 10_000),
            'last_accessed_at' => $this->faker->optional()->dateTime(),
            'is_active'        => $this->faker->boolean(95),
        ];
    }
}
