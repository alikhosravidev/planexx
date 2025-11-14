<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\SubmissionFieldValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\SubmissionFieldValue>
 */
class SubmissionValueFactory extends Factory
{
    protected $model = SubmissionFieldValue::class;

    public function definition(): array
    {
        return [
            'submission_id' => \App\Core\FormEngine\Entities\Submission::factory(),
            'field_id'      => \App\Core\FormEngine\Entities\FormField::factory(),
            'value'         => $this->faker->optional()->word(),
            'file_url'      => $this->faker->optional()->url(),
            'file_metadata' => $this->faker->optional()->randomElements(['size' => 1234], 2),
        ];
    }
}
