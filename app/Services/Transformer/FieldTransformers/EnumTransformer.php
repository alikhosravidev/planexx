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

// TODO: add more tests
class EnumTransformer implements FieldTransformerInterface
{
    public function transform($enum): array|int|string|null
    {
        if (!is_object($enum) || !$enum instanceof \BackedEnum) {
            return $enum;
        }

        return [
            'name'  => $enum->name,
            'value' => $enum->value,
            'label' => method_exists($enum, 'label') ? $enum->label() : null,
            ...$this->transformExtraMethods($enum),
            'cases' => $this->getCasesWithValues(get_class($enum)),
        ];
    }

    /**
     * Transform multiple enum cases.
     *
     * @param array $enums
     * @return array
     */
    public function transformMany(array $enums): array
    {
        if (empty($enums)) {
            return [];
        }

        return array_map(fn ($enum) => $this->transform($enum), $enums);
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

    private function transformExtraMethods($enum): array
    {
        return collect(get_class_methods($enum))
            ->filter(static fn ($method) => ! in_array($method, ['from', 'tryFrom', 'cases', 'label', 'fromName']))
            ->mapWithKeys(static fn ($method) => [
                str($method)->snake()->value() => $enum->{$method}(),
            ])
            ->toArray();
    }
}
