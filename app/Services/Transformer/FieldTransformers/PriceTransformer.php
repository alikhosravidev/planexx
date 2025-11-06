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
use App\Utilities\PriceFormatter;

class PriceTransformer implements FieldTransformerInterface
{
    public function transform($price): array
    {
        $price        = (float) $price;
        $main         = $price;
        $human        = $price > 0 ? PriceFormatter::normal($price) : 'رایگان';
        $summaryHuman = $price > 0 ? PriceFormatter::summary($price) : 'رایگان';
        $readable     = number_format($price);
        $currency     = 'تومان';

        return [
            'main'           => $main,
            'human'          => $human,
            'human_summary'  => $summaryHuman,
            'readable'       => $readable,
            'currency'       => $currency,
            'readable_label' => $human,
        ];
    }
}
