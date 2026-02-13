<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\CustomList;
use Modules\Product\Entities\CustomListItem;

class CustomListItemFactory extends Factory
{
    protected $model = CustomListItem::class;

    public function definition(): array
    {
        return [
            'list_id'        => CustomList::factory(),
            'reference_code' => $this->faker->optional(0.7)->numerify('REF-######'),
            'created_by'     => User::factory(),
        ];
    }
}
