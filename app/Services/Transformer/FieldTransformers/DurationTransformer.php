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
use Carbon\CarbonInterval;

class DurationTransformer implements FieldTransformerInterface
{
    public function transform($duration): array
    {
        return [
            'seconds'   => $duration,
            'minutes'   => $minutes = floor($duration / 60),
            'hours'     => $hours   = floor($duration / 3600),
            'line_time' => $this->convertToTimeLine((int) $duration),
            'human'     => [
                'long'  => CarbonInterval::seconds($duration)->cascade()->forHumans(),
                'short' => $hours > 0 ? "$hours ساعت" : "$minutes دقیقه",
            ],
        ];
    }

    private function convertToTimeLine(float $duration): string
    {
        $hours = str_pad(
            string    : (string) floor($duration / 3600),
            length    : 2,
            pad_string: '0',
            pad_type  : STR_PAD_LEFT
        );
        $minutes = str_pad(
            string    : (string) floor(($duration / 60) % 60),
            length    : 2,
            pad_string: '0',
            pad_type  : STR_PAD_LEFT
        );
        $seconds = str_pad(
            string    : (string) ($duration % 60),
            length    : 2,
            pad_string: '0',
            pad_type  : STR_PAD_LEFT
        );

        return '00' === $hours ? "{$minutes}:{$seconds}" : "{$hours}:{$minutes}:{$seconds}";
    }
}
