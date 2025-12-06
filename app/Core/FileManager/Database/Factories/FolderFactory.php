<?php

declare(strict_types=1);

namespace App\Core\FileManager\Database\Factories;

use App\Core\FileManager\Entities\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

class FolderFactory extends Factory
{
    protected $model = Folder::class;

    public function definition(): array
    {
        return [
            'parent_id'   => null,
            'module_name' => $this->faker->optional()->randomElement(['users', 'documents', 'forms', 'bpms']),
            'name'        => $this->faker->words(2, true),
            'is_public'   => $this->faker->boolean(30),
        ];
    }
}
