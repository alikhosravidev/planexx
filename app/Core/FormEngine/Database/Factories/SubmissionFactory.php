<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\Submission>
 */
class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        return [
            'form_id'      => \App\Core\FormEngine\Entities\Form::factory(),
            'user_id'      => null, // or User::factory()
            'user_name'    => $this->faker->optional()->name(),
            'user_mobile'  => $this->faker->optional()->phoneNumber(),
            'is_verified'  => $this->faker->boolean(),
            'ip'           => $this->faker->ipv4(),
            'user_agent'   => $this->faker->userAgent(),
            'utm_params'   => $this->faker->optional()->randomElements(['utm_source' => 'google'], 2),
            'submitted_at' => $this->faker->dateTime(),
            'metadata'     => $this->faker->optional()->randomElements(['referrer' => 'example.com'], 2),
        ];
    }
}
