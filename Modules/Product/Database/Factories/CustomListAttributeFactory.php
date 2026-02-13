<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\CustomList;
use Modules\Product\Entities\CustomListAttribute;
use Modules\Product\Enums\AttributeDataTypeEnum;

class CustomListAttributeFactory extends Factory
{
    protected $model = CustomListAttribute::class;

    public function definition(): array
    {
        return [
            'list_id'     => CustomList::factory(),
            'label'       => $this->faker->word(),
            'key_name'    => $this->faker->unique()->slug(1),
            'data_type'   => $this->faker->randomElement(AttributeDataTypeEnum::cases())->value,
            'is_required' => $this->faker->boolean(30),
            'sort_order'  => $this->faker->numberBetween(0, 50),
        ];
    }

    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => true,
        ]);
    }

    public function ofType(AttributeDataTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'data_type' => $type->value,
        ]);
    }
}
