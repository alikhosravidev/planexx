<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\CustomListAttribute;
use Modules\Product\Entities\CustomListItem;
use Modules\Product\Entities\CustomListValue;

class CustomListValueFactory extends Factory
{
    protected $model = CustomListValue::class;

    public function definition(): array
    {
        return [
            'item_id'       => CustomListItem::factory(),
            'attribute_id'  => CustomListAttribute::factory(),
            'value_text'    => $this->faker->optional()->sentence(),
            'value_number'  => null,
            'value_date'    => null,
            'value_boolean' => null,
        ];
    }

    public function withTextValue(string $text = null): static
    {
        return $this->state(fn (array $attributes) => [
            'value_text'    => $text ?? $this->faker->sentence(),
            'value_number'  => null,
            'value_date'    => null,
            'value_boolean' => null,
        ]);
    }

    public function withNumberValue(float $number = null): static
    {
        return $this->state(fn (array $attributes) => [
            'value_text'    => null,
            'value_number'  => $number ?? $this->faker->randomFloat(4, 0, 99999),
            'value_date'    => null,
            'value_boolean' => null,
        ]);
    }

    public function withDateValue(string $date = null): static
    {
        return $this->state(fn (array $attributes) => [
            'value_text'    => null,
            'value_number'  => null,
            'value_date'    => $date ?? $this->faker->dateTime(),
            'value_boolean' => null,
        ]);
    }

    public function withBooleanValue(bool $value = null): static
    {
        return $this->state(fn (array $attributes) => [
            'value_text'    => null,
            'value_number'  => null,
            'value_date'    => null,
            'value_boolean' => $value ?? $this->faker->boolean(),
        ]);
    }
}
