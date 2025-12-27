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
use Carbon\Carbon;
use InvalidArgumentException;

class DateTimeTransformer implements FieldTransformerInterface
{
    public function transform($date): array
    {
        $date = $this->normalizeAndValidate($date);

        $main    = (string) $date;
        $default = $date->format('Y-m-d');

        return [
            'main'    => $main,
            'default' => $default,
            'hour'    => $date->hour,
            'minute'  => $date->minute,
            'second'  => $date->second,
            'human'   => array_merge(
                $this->getJalaliData($date),
                ['gregorian' => $this->getGregorianData($date)]
            ),
        ];
    }

    private function getJalaliData(Carbon $date): array
    {
        $jalaliDate   = verta($date);
        $diffForHuman = $date->diffForHumans();

        return [
            'main'      => $jalaliDate->format('Y-m-d H:i:s'),
            'default'   => $jalaliDate->format('j %B Y'),
            'short'     => $jalaliDate->format('Y/n/j'),
            'long'      => $jalaliDate->format('j %B Y ساعت H:i'),
            'dayOfWeek' => $jalaliDate->format('%A'),
            'month'     => $jalaliDate->format('%B'),
            'year'      => $jalaliDate->year,
            'dayMonth'  => $jalaliDate->format('j %B'),
            'diff'      => $diffForHuman,
        ];
    }

    private function getGregorianData(Carbon $date): array
    {
        $englishDate    = $date->copy()->locale('en');
        $short          = $date->format('y-m-d');
        $year           = (int) $date->format('Y');
        $monthName      = $date->format('M');
        $enDiffForHuman = $englishDate->diffForHumans();

        return [
            'default'   => $short,
            'short'     => $short,
            'long'      => $englishDate->format('F j, Y H:i'),
            'dayOfWeek' => $englishDate->format('l'),
            'month'     => $monthName,
            'year'      => $year,
            'diff'      => $enDiffForHuman,
        ];
    }

    private function normalizeAndValidate($date): Carbon
    {
        if (\is_string($date)) {
            $date = Carbon::parse($date);
        }

        if (! $date instanceof Carbon) {
            throw new InvalidArgumentException(
                \sprintf(
                    'Invalid data type `%s` in `%s` transform method',
                    \gettype($date),
                    __CLASS__
                ),
            );
        }

        return $date;
    }
}
