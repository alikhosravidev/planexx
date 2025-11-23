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

namespace App\Core\Notify\Services\SmsServiceProvider\SmsDataNormalizers;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsDataNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\DTOs\ReceivedSmsDTO;

class SmsIrDataNormalizer implements SmsDataNormalizer
{
    /**
     * @return array<ReceivedSmsDTO>
     */
    public function normalize(mixed $data): array
    {
        if (empty($data)) {
            return [];
        }

        $normalItems = array_map(
            callback: static function ($item) {
                if (empty($item->MessageText) || mb_strlen($item->MessageText, 'UTF-8') > 256) {
                    return null;
                }

                if (empty($item->Mobile)) {
                    return null;
                }

                return new ReceivedSmsDTO(
                    message: $item->MessageText,
                    sender: (string) $item->Mobile   ?? '',
                    receptor: (string) $item->Number ?? '',
                    date: $item->ReceivedDateTime    ?? ''
                );
            },
            array   : $data
        );

        return array_values(
            array_filter($normalItems)
        );
    }
}
