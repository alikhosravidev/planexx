<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Services\Transformer\FieldTransformers;

use App\Contracts\Transformer\FieldTransformerInterface;
use BackedEnum;

class EnumTransformer implements FieldTransformerInterface
{
    public function transform($enum): array
    {
        return [
            'name'  => $enum->name,
            'value' => $enum->value,
            'label' => method_exists($enum, 'label') ? $enum->label() : null,
            'cases' => $this->getCasesWithValues($enum::class),
        ];
    }

    private function getCasesWithValues(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(static fn (BackedEnum $case) => [
                $case->name => [
                    'value' => $case->value,
                    'label' => method_exists($case, 'label') ? $case->label() : null,
                ],
            ])
            ->toArray()
        ;
    }
}
