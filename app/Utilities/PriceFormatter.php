<?php

declare(strict_types=1);

namespace App\Utilities;

class PriceFormatter
{
    private static array $formatters = [
        1_000_000_000_000 => [
            'normal'    => 'هزار میلیارد تومان',
            'summary'   => 'ه.م.ت',
            'precision' => 3,
        ],
        1_000_000_000 => [
            'normal'    => 'میلیارد تومان',
            'summary'   => 'میلیارد.ت',
            'precision' => 3,
        ],
        1_000_000 => [
            'normal'    => 'میلیون تومان',
            'summary'   => 'م.ت',
            'precision' => 1,
        ],
        1_000 => [
            'normal'    => 'هزار تومان',
            'summary'   => 'ه.ت',
            'precision' => 1,
        ],
        1 => [
            'normal'    => 'تومان',
            'summary'   => 'ت',
            'precision' => 1,
        ],
    ];

    public static function normal(float|string $amount, string $format = '%.1f %s'): string
    {
        if (! is_numeric($amount)) {
            throw new \InvalidArgumentException('The toman param is invalid!');
        }

        return self::format((int) $amount, $format, 'normal');
    }

    public static function summary(float|string $amount, string $format = '%.1f %s'): string
    {
        if (! is_numeric($amount)) {
            throw new \InvalidArgumentException('The toman param is invalid!');
        }

        return self::format((int) $amount, $format, 'summary');
    }

    private static function format(float $amount, string $format, string $formatter): string
    {
        list($digit, $label, $precision) = self::getDigitAndLabel($amount, $formatter);

        if ($precision > 1) {
            $format = "%.{$precision}f %s";
        }

        if (floor($digit) === $digit || 0 === $digit) {
            $format = preg_replace('/(%)(.*?)(f)/', '%d', $format);
        }

        return sprintf($format, $digit, $label);
    }

    private static function getDigitAndLabel(float $amount, string $formatter): array
    {
        $digit = 0;
        $label = '';

        $precision = 1;

        foreach (self::$formatters as $threshold => $label) {
            if ($amount >= $threshold) {
                $precision = $label['precision'];
                $digit     = round($amount / $threshold, $precision);

                break;
            }
        }

        return [$digit, $label[$formatter], $precision];
    }
}
